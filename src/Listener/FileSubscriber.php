<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Events;
use Leapt\CoreBundle\Doctrine\Mapping\File as FileAttribute;
use Leapt\CoreBundle\File\CondemnedFile;
use Leapt\CoreBundle\Util\StringUtil;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PropertyAccess\PropertyAccess;

class FileSubscriber implements EventSubscriber
{
    private array $config = [];
    private array $unlinkQueue = [];

    public function __construct(private string $uploadDir)
    {
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

    public function preFlush(PreFlushEventArgs $ea): void
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

    public function onFlush(OnFlushEventArgs $ea): void
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

    public function postPersist(LifecycleEventArgs $ea): void
    {
        $this->postSave($ea);
    }

    public function postUpdate(LifecycleEventArgs $ea): void
    {
        $this->postSave($ea);
    }

    public function preRemove(LifecycleEventArgs $ea): void
    {
        $entity = $ea->getEntity();
        foreach ($this->getFileFields($entity, $ea->getEntityManager()) as $fileConfig) {
            $this->preRemoveUpload($entity, $fileConfig);
        }
    }

    public function postRemove(LifecycleEventArgs $ea): void
    {
        $entity = $ea->getEntity();
        foreach ($this->getFileFields($entity, $ea->getEntityManager()) as $fileConfig) {
            $this->removeUpload($entity, $fileConfig);
        }
    }

    /**
     * Return all the file fields for the provided entity.
     */
    private function getFileFields(object $entity, EntityManagerInterface $em): array
    {
        $className = \get_class($entity);
        $this->checkClassConfig($entity, $em);

        if (\array_key_exists($className, $this->config)) {
            return $this->config[$className]['fields'];
        }

        return [];
    }

    private function postSave(LifecycleEventArgs $ea): void
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

    private function preUpload($ea, mixed $fileEntity, array $fileConfig): void
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
                \assert($propertyValue instanceof UploadedFile);
                $newFilename = $propertyValue->getClientOriginalName();

                $fileConfig['meta']->setFieldValue($fileEntity, $fileConfig['filename'], $newFilename);
                $entityManager->getUnitOfWork()->propertyChanged($fileEntity, $fileConfig['filename'], $oldFilename, $newFilename);
            }
        }
    }

    private function upload(LifecycleEventArgs $ea, object $fileEntity, array $fileConfig): void
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

    private function preRemoveUpload(object $fileEntity, array $fileConfig): void
    {
        $mappedValue = $fileConfig['meta']->getFieldValue($fileEntity, $fileConfig['mappedBy']);

        if (!empty($mappedValue)) {
            $fileConfig['meta']->setFieldValue($fileEntity, $fileConfig['mappedBy'], null);
            $this->unlinkQueue[spl_object_hash($fileEntity)] = $this->uploadDir . '/' . $mappedValue;
        }
    }

    private function removeUpload(object $fileEntity, array $fileConfig): void
    {
        if (isset($this->unlinkQueue[spl_object_hash($fileEntity)]) && is_file($this->unlinkQueue[spl_object_hash($fileEntity)])) {
            unlink($this->unlinkQueue[spl_object_hash($fileEntity)]);
        }
        $fileConfig['property']->setValue($fileEntity, null);
    }

    private function generateFileName(object $fileEntity, array $fileConfig): string
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

    private function checkClassConfig($entity, EntityManagerInterface $entityManager): void
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
                $attributes = $this->getAttributes($property);
                foreach ($attributes as $attribute) {
                    $property->setAccessible(true);
                    $field = $property->getName();

                    if (null === $attribute->mappedBy) {
                        throw new \InvalidArgumentException(sprintf('Parameter "mappedBy" of LeaptCore\File declared on %s expects another class property to map onto. This value should not be null.', $meta->getReflectionClass()->getName()));
                    }
                    if (null === $attribute->path && null === $attribute->pathCallback) {
                        throw new \InvalidArgumentException(sprintf('Attribute #%s declared on %s expects "path" or "pathCallback". One of them should not be null.', 'LeaptCore\File', $meta->getReflectionClass()->getName()));
                    }
                    if (!$meta->hasField($attribute->mappedBy)) {
                        throw new \InvalidArgumentException(sprintf('The entity "%s" has no field named "%s", but it is documented in the attribute @%s', $meta->getReflectionClass()->getName(), $attribute->mappedBy, 'LeaptCore\File'));
                    }

                    $this->config[$class]['fields'][$field] = [
                        'property'     => $property,
                        'path'         => $attribute->path,
                        'mappedBy'     => $attribute->mappedBy,
                        'filename'     => $attribute->filename,
                        'meta'         => $meta,
                        'nameCallback' => $attribute->nameCallback,
                        'pathCallback' => $attribute->pathCallback,
                    ];
                }
            }
        }
    }

    /**
     * @return iterable|FileAttribute[]
     */
    private function getAttributes(\ReflectionProperty $reflection): iterable
    {
        foreach ($reflection->getAttributes(FileAttribute::class) as $attribute) {
            yield $attribute->newInstance();
        }
    }
}
