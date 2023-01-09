<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Events;
use Leapt\CoreBundle\Doctrine\Mapping\File as FileAttribute;
use Leapt\CoreBundle\File\CondemnedFile;
use Leapt\CoreBundle\FileStorage\FileStorageManager;
use Leapt\CoreBundle\FileStorage\FileUploadConfig;
use Leapt\CoreBundle\Util\StringUtil;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PropertyAccess\PropertyAccess;

class FileSubscriber implements EventSubscriber
{
    /**
     * @var array<class-string, array<string, array<string, FileUploadConfig>>>
     */
    private array $config = [];
    private array $unlinkQueue = [];

    public function __construct(private FileStorageManager $fileStorageManager)
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
        $entityManager = $ea->getObjectManager();

        // Hit fix, see http://doctrine-project.org/jira/browse/DDC-2276
        // @todo: wait for real fix
        if (!$entityManager instanceof EntityManager) {
            return;
        }
        $unitOfWork = $entityManager->getUnitOfWork();

        // Finally, check all entities in identity map - if they have a file object they need to be processed
        foreach ($unitOfWork->getIdentityMap() as $entities) {
            foreach ($entities as $fileEntity) {
                foreach ($this->getFileFields($fileEntity, $entityManager) as $fileUploadConfig) {
                    $propertyValue = $fileUploadConfig->property->getValue($fileEntity);
                    if ($propertyValue instanceof CondemnedFile) {
                        $this->preRemoveUpload($fileEntity, $fileUploadConfig);
                    } else {
                        $this->preUpload($ea, $fileEntity, $fileUploadConfig);
                    }
                }
            }
        }
    }

    public function onFlush(OnFlushEventArgs $ea): void
    {
        $entityManager = $ea->getObjectManager();

        // Hit fix, see http://doctrine-project.org/jira/browse/DDC-2276
        // @todo: wait for real fix
        if (!$entityManager instanceof EntityManager) {
            return;
        }
        $unitOfWork = $entityManager->getUnitOfWork();

        // Then, let's deal with entities schedules for insertion
        foreach ($unitOfWork->getScheduledEntityInsertions() as $fileEntity) {
            foreach ($this->getFileFields($fileEntity, $entityManager) as $fileUploadConfig) {
                $this->preUpload($ea, $fileEntity, $fileUploadConfig);
            }
        }
    }

    public function postPersist(PostPersistEventArgs $ea): void
    {
        $this->postSave($ea);
    }

    public function postUpdate(PostUpdateEventArgs $ea): void
    {
        $this->postSave($ea);
    }

    public function preRemove(PreRemoveEventArgs $ea): void
    {
        $entity = $ea->getObject();
        foreach ($this->getFileFields($entity, $ea->getObjectManager()) as $fileConfig) {
            $this->preRemoveUpload($entity, $fileConfig);
        }
    }

    public function postRemove(PostRemoveEventArgs $ea): void
    {
        $entity = $ea->getObject();
        foreach ($this->getFileFields($entity, $ea->getObjectManager()) as $fileConfig) {
            $this->removeUpload($entity, $fileConfig);
        }
    }

    /**
     * Return all the file fields for the provided entity.
     *
     * @return array<string, FileUploadConfig>
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

    private function postSave(PostPersistEventArgs|PostUpdateEventArgs $ea): void
    {
        $fileEntity = $ea->getObject();
        foreach ($this->getFileFields($fileEntity, $ea->getObjectManager()) as $fileUploadConfig) {
            $propertyValue = $fileUploadConfig->property->getValue($fileEntity);
            if ($propertyValue instanceof CondemnedFile) {
                $this->removeUpload($fileEntity, $fileUploadConfig);
            } else {
                $this->upload($ea, $fileEntity, $fileUploadConfig);
            }
        }
    }

    private function preUpload(PreFlushEventArgs|OnFlushEventArgs $ea, mixed $fileEntity, FileUploadConfig $fileUploadConfig): void
    {
        $propertyValue = $fileUploadConfig->property->getValue($fileEntity);
        if ($propertyValue instanceof File) {
            $oldMappedValue = $fileUploadConfig->classMetadata->getFieldValue($fileEntity, $fileUploadConfig->attribute->mappedBy);
            $newMappedValue = $this->generateFileName($fileEntity, $fileUploadConfig);
            $fileUploadConfig->classMetadata->setFieldValue($fileEntity, $fileUploadConfig->attribute->mappedBy, $newMappedValue);

            $entityManager = $ea->getObjectManager();
            $entityManager->getUnitOfWork()->propertyChanged($fileEntity, $fileUploadConfig->attribute->mappedBy, $oldMappedValue, $newMappedValue);

            if (null !== $fileUploadConfig->attribute->filename) {
                $oldFilename = $fileUploadConfig->classMetadata->getFieldValue($fileEntity, $fileUploadConfig->attribute->filename);
                \assert($propertyValue instanceof UploadedFile);
                $newFilename = $propertyValue->getClientOriginalName();

                $fileUploadConfig->classMetadata->setFieldValue($fileEntity, $fileUploadConfig->attribute->filename, $newFilename);
                $entityManager->getUnitOfWork()->propertyChanged($fileEntity, $fileUploadConfig->attribute->filename, $oldFilename, $newFilename);
            }
        }
    }

    private function upload(PostPersistEventArgs|PostUpdateEventArgs $ea, object $fileEntity, FileUploadConfig $fileUploadConfig): void
    {
        $propertyValue = $fileUploadConfig->property->getValue($fileEntity);
        if (!$propertyValue instanceof File) {
            return;
        }

        $mappedValue = $fileUploadConfig->classMetadata->getFieldValue($fileEntity, $fileUploadConfig->attribute->mappedBy);
        $filename = basename($mappedValue);
        $path = \dirname($mappedValue);

        $this->fileStorageManager->uploadFile($fileUploadConfig, $propertyValue, $path, $filename);

        // Remove previous file
        $unitOfWork = $ea->getObjectManager()->getUnitOfWork();
        $changeSet = $unitOfWork->getEntityChangeSet($fileEntity);
        if (\array_key_exists($fileUploadConfig->attribute->mappedBy, $changeSet)) {
            $oldValue = $changeSet[$fileUploadConfig->attribute->mappedBy][0];
            if (null !== $oldValue) {
                $this->fileStorageManager->removeFile($fileUploadConfig, $oldValue);
            }
        }

        $fileUploadConfig->property->setValue($fileEntity, null);
    }

    private function preRemoveUpload(object $fileEntity, FileUploadConfig $fileUploadConfig): void
    {
        $mappedValue = $fileUploadConfig->classMetadata->getFieldValue($fileEntity, $fileUploadConfig->attribute->mappedBy);

        if (!empty($mappedValue)) {
            $fileUploadConfig->classMetadata->setFieldValue($fileEntity, $fileUploadConfig->attribute->mappedBy, null);
            $this->unlinkQueue[spl_object_hash($fileEntity)][$fileUploadConfig->property->getName()] = $mappedValue;
        }
    }

    private function removeUpload(object $fileEntity, FileUploadConfig $fileUploadConfig): void
    {
        if (isset($this->unlinkQueue[spl_object_hash($fileEntity)][$fileUploadConfig->property->getName()])) {
            $this->fileStorageManager->removeFile($fileUploadConfig, $this->unlinkQueue[spl_object_hash($fileEntity)][$fileUploadConfig->property->getName()]);
        }
        $fileUploadConfig->property->setValue($fileEntity, null);
    }

    private function generateFileName(object $fileEntity, FileUploadConfig $fileUploadConfig): string
    {
        $path = $fileUploadConfig->attribute->path;
        if (null !== $fileUploadConfig->attribute->pathCallback) {
            $accessor = PropertyAccess::createPropertyAccessor();
            $path = $accessor->getValue($fileEntity, $fileUploadConfig->attribute->pathCallback);
        }
        $path .= '/';
        $ext = '.' . $fileUploadConfig->property->getValue($fileEntity)->guessExtension();

        if (null !== $fileUploadConfig->attribute->nameCallback) {
            $accessor = PropertyAccess::createPropertyAccessor();
            $filename = $accessor->getValue($fileEntity, $fileUploadConfig->attribute->nameCallback);
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
                    if (null === $attribute->path && null === $attribute->pathCallback && null === $attribute->flysystemConfig) {
                        throw new \InvalidArgumentException(sprintf('Attribute #%s declared on %s expects "path", "pathCallback" or "flysystemConfig". One of them should not be null.', 'LeaptCore\File', $meta->getReflectionClass()->getName()));
                    }
                    if (!$meta->hasField($attribute->mappedBy)) {
                        throw new \InvalidArgumentException(sprintf('The entity "%s" has no field named "%s", but it is documented in the attribute @%s', $meta->getReflectionClass()->getName(), $attribute->mappedBy, 'LeaptCore\File'));
                    }

                    $this->config[$class]['fields'][$field] = new FileUploadConfig($property, $attribute, $meta);
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
