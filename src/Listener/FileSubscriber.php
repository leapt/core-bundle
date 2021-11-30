<?php

namespace Leapt\CoreBundle\Listener;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Events;
use Leapt\CoreBundle\Doctrine\Mapping\File as FileAnnotation;
use Leapt\CoreBundle\File\CondemnedFile;
use Leapt\CoreBundle\Util\StringUtil;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\PropertyAccess\PropertyAccess;

class FileSubscriber implements EventSubscriber
{
    private array $config = [];

    private array $unlinkQueue = [];

    private AnnotationReader $reader;

    public function __construct(private string $uploadDir)
    {
        $this->reader = new AnnotationReader();
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::preFlush,
            Events::onFlush,
            Events::postPersist,
            Events::postUpdate,
            Events::preRemove,
            Events::postRemove,
        ];
    }

    public function preFlush(PreFlushEventArgs $ea)
    {
        $entityManager = $ea->getEntityManager();

        // Hit fix, see http://doctrine-project.org/jira/browse/DDC-2276
        // @todo: wait for real fix
        if (!$entityManager instanceof EntityManager) {
            return;
        }
        $unitOfWork = $entityManager->getUnitOfWork();

        // Finally, check all entities in identity map - if they have a file object they need to be processed
        foreach ($unitOfWork->getIdentityMap() as $entities) {
            foreach ($entities as $fileEntity) {
                foreach ($this->getFileFields($fileEntity, $entityManager) as $fileConfig) {
                    $propertyValue = $fileConfig['property']->getValue($fileEntity);
                    if ($propertyValue instanceof CondemnedFile) {
                        $this->preRemoveUpload($fileEntity, $fileConfig);
                    } else {
                        $this->preUpload($ea, $fileEntity, $fileConfig);
                    }
                }
            }
        }
    }

    public function onFlush(OnFlushEventArgs $ea)
    {
        $entityManager = $ea->getEntityManager();

        // Hit fix, see http://doctrine-project.org/jira/browse/DDC-2276
        // @todo: wait for real fix
        if (!$entityManager instanceof EntityManager) {
            return;
        }
        $unitOfWork = $entityManager->getUnitOfWork();

        // Then, let's deal with entities schedules for insertion
        foreach ($unitOfWork->getScheduledEntityInsertions() as $fileEntity) {
            foreach ($this->getFileFields($fileEntity, $entityManager) as $fileConfig) {
                $this->preUpload($ea, $fileEntity, $fileConfig);
            }
        }
    }

    public function postPersist(LifecycleEventArgs $ea)
    {
        $this->postSave($ea);
    }

    public function postUpdate(LifecycleEventArgs $ea)
    {
        $this->postSave($ea);
    }

    public function preRemove(LifecycleEventArgs $ea)
    {
        $entity = $ea->getEntity();
        foreach ($this->getFileFields($entity, $ea->getEntityManager()) as $fileConfig) {
            $this->preRemoveUpload($entity, $fileConfig);
        }
    }

    public function postRemove(LifecycleEventArgs $ea)
    {
        $entity = $ea->getEntity();
        foreach ($this->getFileFields($entity, $ea->getEntityManager()) as $fileConfig) {
            $this->removeUpload($entity, $fileConfig);
        }
    }

    /**
     * Return all the file fields for the provided entity.
     *
     * @param $entity
     */
    private function getFileFields($entity, EntityManager $em): array
    {
        $className = \get_class($entity);
        $this->checkClassConfig($entity, $em);

        if (\array_key_exists($className, $this->config)) {
            return $this->config[$className]['fields'];
        }

        return [];
    }

    private function postSave(LifecycleEventArgs $ea)
    {
        $fileEntity = $ea->getEntity();
        foreach ($this->getFileFields($fileEntity, $ea->getEntityManager()) as $fileConfig) {
            $propertyValue = $fileConfig['property']->getValue($fileEntity);
            if ($propertyValue instanceof CondemnedFile) {
                $this->removeUpload($fileEntity, $fileConfig);
            } else {
                $this->upload($ea, $fileEntity, $fileConfig);
            }
        }
    }

    /**
     * @param $ea
     * @param $fileEntity
     */
    private function preUpload($ea, $fileEntity, array $fileConfig)
    {
        $propertyValue = $fileConfig['property']->getValue($fileEntity);
        if ($propertyValue instanceof File) {
            $oldMappedValue = $fileConfig['meta']->getFieldValue($fileEntity, $fileConfig['mappedBy']);
            $newMappedValue = $this->generateFileName($fileEntity, $fileConfig);
            $fileConfig['meta']->setFieldValue($fileEntity, $fileConfig['mappedBy'], $newMappedValue);

            $entityManager = $ea->getEntityManager();
            $entityManager->getUnitOfWork()->propertyChanged($fileEntity, $fileConfig['mappedBy'], $oldMappedValue, $newMappedValue);

            if (null !== $fileConfig['filename']) {
                $oldFilename = $fileConfig['meta']->getFieldValue($fileEntity, $fileConfig['filename']);
                $newFilename = $propertyValue->getClientOriginalName();

                $fileConfig['meta']->setFieldValue($fileEntity, $fileConfig['filename'], $newFilename);
                $entityManager->getUnitOfWork()->propertyChanged($fileEntity, $fileConfig['filename'], $oldFilename, $newFilename);
            }
        }
    }

    /**
     * @param $fileEntity
     */
    private function upload(LifecycleEventArgs $ea, $fileEntity, array $fileConfig)
    {
        $propertyValue = $fileConfig['property']->getValue($fileEntity);
        if (!$propertyValue instanceof File) {
            return;
        }

        $mappedValue = $fileConfig['meta']->getFieldValue($fileEntity, $fileConfig['mappedBy']);
        $filename = basename($mappedValue);
        $path = \dirname($mappedValue);

        $propertyValue->move($this->uploadDir . '/' . $path, $filename);

        // Remove previous file
        $unitOfWork = $ea->getEntityManager()->getUnitOfWork();
        $changeSet = $unitOfWork->getEntityChangeSet($fileEntity);
        if (\array_key_exists($fileConfig['mappedBy'], $changeSet)) {
            $oldvalue = $changeSet[$fileConfig['mappedBy']][0];
            if (null !== $oldvalue) {
                @unlink($this->uploadDir . '/' . $oldvalue);
            }
        }

        $fileConfig['property']->setValue($fileEntity, null);
    }

    /**
     * @param $fileEntity
     */
    private function preRemoveUpload($fileEntity, array $fileConfig)
    {
        $mappedValue = $fileConfig['meta']->getFieldValue($fileEntity, $fileConfig['mappedBy']);

        if (!empty($mappedValue)) {
            $fileConfig['meta']->setFieldValue($fileEntity, $fileConfig['mappedBy'], null);
            $this->unlinkQueue[spl_object_hash($fileEntity)] = $this->uploadDir . '/' . $mappedValue;
        }
    }

    /**
     * @param $fileEntity
     */
    private function removeUpload($fileEntity, array $fileConfig)
    {
        if (isset($this->unlinkQueue[spl_object_hash($fileEntity)]) && is_file($this->unlinkQueue[spl_object_hash($fileEntity)])) {
            unlink($this->unlinkQueue[spl_object_hash($fileEntity)]);
        }
        $fileConfig['property']->setValue($fileEntity, null);
    }

    /**
     * @param $fileEntity
     */
    private function generateFileName($fileEntity, array $fileConfig): string
    {
        $path = $fileConfig['path'];
        if (null !== $fileConfig['pathCallback']) {
            $accessor = PropertyAccess::createPropertyAccessor();
            $path = $accessor->getValue($fileEntity, $fileConfig['pathCallback']);
        }
        $path .= '/';
        $ext = '.' . $fileConfig['property']->getValue($fileEntity)->guessExtension();

        if (null !== $fileConfig['nameCallback']) {
            $accessor = PropertyAccess::createPropertyAccessor();
            $filename = $accessor->getValue($fileEntity, $fileConfig['nameCallback']);
            $filename = StringUtil::slugify($filename);

            /*
             * Here we add a uniqid at the end of the filename to avoid any cache issue
             */
            $filename .= '-' . uniqid();
        } else {
            $filename = uniqid();
        }

        return $path . $filename . $ext;
    }

    private function checkClassConfig($entity, EntityManager $entityManager)
    {
        $class = \get_class($entity);

        if (!\array_key_exists($class, $this->config)) {
            $meta = $entityManager->getClassMetaData($class);
            foreach ($meta->getReflectionClass()->getProperties() as $property) {
                if ($meta->isMappedSuperclass && !$property->isPrivate() ||
                    $meta->isInheritedField($property->name) ||
                    isset($meta->associationMappings[$property->name]['inherited'])
                ) {
                    continue;
                }
                $annotations = $this->getAnnotations($property);
                foreach ($annotations as $annotation) {
                    $property->setAccessible(true);
                    $field = $property->getName();

                    if (null === $annotation->mappedBy) {
                        throw AnnotationException::requiredError('mappedBy', 'LeaptCore\File', $meta->getReflectionClass()->getName(), 'another class property to map onto');
                    }
                    if (null === $annotation->path && null === $annotation->pathCallback) {
                        throw AnnotationException::syntaxError(sprintf('Annotation @%s declared on %s expects "path" or "pathCallback". One of them should not be null.', 'LeaptCore\File', $meta->getReflectionClass()->getName()));
                    }
                    if (!$meta->hasField($annotation->mappedBy)) {
                        throw AnnotationException::syntaxError(sprintf('The entity "%s" has no field named "%s", but it is documented in the annotation @%s', $meta->getReflectionClass()->getName(), $annotation->mappedBy, 'LeaptCore\File'));
                    }

                    $this->config[$class]['fields'][$field] = [
                        'property'     => $property,
                        'path'         => $annotation->path,
                        'mappedBy'     => $annotation->mappedBy,
                        'filename'     => $annotation->filename,
                        'meta'         => $meta,
                        'nameCallback' => $annotation->nameCallback,
                        'pathCallback' => $annotation->pathCallback,
                    ];
                }
            }
        }
    }

    /**
     * @return iterable|FileAnnotation[]
     */
    private function getAnnotations(\ReflectionProperty $reflection): iterable
    {
        if (\PHP_VERSION_ID >= 80000) {
            foreach ($reflection->getAttributes(FileAnnotation::class) as $attribute) {
                yield $attribute->newInstance();
            }
        }

        $annotations = $this->reader->getPropertyAnnotations($reflection);
        foreach ($annotations as $annotation) {
            if ($annotation instanceof FileAnnotation) {
                yield $annotation;
            }
        }
    }
}
