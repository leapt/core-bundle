<?php

namespace Snowcap\CoreBundle\Listener;

use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;

class FileSubscriber implements EventSubscriber
{
    /**
     * @var array
     */
    private $config = array();

    /**
     * @var string
     */
    private $uploadDir;

    /**
     * @param string $uploadDir
     */
    public function __construct($uploadDir)
    {
        $this->uploadDir = $uploadDir;
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array('loadClassMetadata', 'preFlush', 'postPersist', 'postUpdate', 'postRemove');
    }

    /**
     * @param \Doctrine\ORM\Event\LoadClassMetadataEventArgs $eventArgs
     * @throws \UnexpectedValueException
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $reader = new \Doctrine\Common\Annotations\AnnotationReader();
        $meta = $eventArgs->getClassMetadata();
        foreach ($meta->getReflectionClass()->getProperties() as $property) {
            if ($meta->isMappedSuperclass && !$property->isPrivate() ||
                $meta->isInheritedField($property->name) ||
                isset($meta->associationMappings[$property->name]['inherited'])
            ) {
                continue;
            }
            if ($annotation = $reader->getPropertyAnnotation($property, 'Snowcap\\CoreBundle\\Doctrine\\Mapping\\File')) {
                $property->setAccessible(true);
                $field = $property->getName();

                //TODO: Improve validation
                if (!$meta->hasField($annotation->mappedBy)) {
                    $exceptionMessage = 'The entity "%s" has no field named "%s", but it is documented in a Snowcap File annotation';
                    throw new \UnexpectedValueException(sprintf($exceptionMessage, $meta->getReflectionClass()->getName(), $annotation->mappedBy));
                }

                $this->config[$meta->getName()]['fields'][$field] = array(
                    'property' => $property,
                    'path' => $annotation->path,
                    'mappedBy' => $annotation->mappedBy,
                    'filename' => $annotation->filename,
                    'meta' => $meta
                );
            }
        }
    }

    /**
     * @param \Doctrine\ORM\Event\PreFlushEventArgs $ea
     */
    public function preFlush(PreFlushEventArgs $ea)
    {
        $unitOfWork = $ea->getEntityManager()->getUnitOfWork();

        $entitiesToInsertOrUpdate = array_merge($unitOfWork->getScheduledEntityInsertions(), $unitOfWork->getScheduledEntityUpdates());
        foreach($entitiesToInsertOrUpdate as $entity) {
            foreach ($this->getFileFields($entity, $ea->getEntityManager()) as $file) {
                $this->preUpload($ea, $entity, $file);
            }
        }
    }

    /**
     * Return all the file fields for the provided entity
     *
     * @param $entity
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @return array
     */
    private function getFileFields($entity, \Doctrine\ORM\EntityManager $entityManager)
    {
        $classMetaData = $entityManager->getClassMetaData(get_class($entity));
        $className = $classMetaData->getName();

        if (array_key_exists($className, $this->config)) {
            return $this->config[$className]['fields'];
        }
        return array();
    }

    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $ea
     */
    public function postPersist(LifecycleEventArgs $ea)
    {
        $this->postSave($ea);
    }

    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $ea
     */
    public function postUpdate(LifecycleEventArgs $ea)
    {
        $this->postSave($ea);
    }

    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $ea
     */
    public function postRemove(LifecycleEventArgs $ea)
    {
        $entity = $ea->getEntity();
        foreach ($this->getFileFields($entity, $ea->getEntityManager()) as $file) {
            $this->removeUpload($entity, $file);
        }
    }

    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $ea
     */
    private function postSave(LifecycleEventArgs $ea)
    {
        $entity = $ea->getEntity();
        foreach ($this->getFileFields($entity, $ea->getEntityManager()) as $file) {
            $this->upload($ea, $entity, $file);
        }
    }

    /**
     * @param $ea
     * @param $fileEntity
     * @param array $fileConfig
     */
    private function preUpload($ea, $fileEntity, array $fileConfig)
    {
        $propertyValue = $fileConfig['property']->getValue($fileEntity);
        if ($propertyValue instanceof File) {
            $oldMappedValue = $fileConfig['meta']->getFieldValue($fileEntity, $fileConfig['mappedBy']);
            $newMappedValue = $this->generateFileName($fileEntity, $fileConfig);
            $fileConfig['meta']->setFieldValue($fileEntity, $fileConfig['mappedBy'], $newMappedValue);

            if ($fileConfig['filename'] !== null) {
                $fileConfig['meta']->setFieldValue($fileEntity, $fileConfig['filename'], $propertyValue->getClientOriginalName());
            }

            $entityManager = $ea->getEntityManager();
            $entityManager->getUnitOfWork()->propertyChanged($fileEntity, $fileConfig['mappedBy'], $oldMappedValue, $newMappedValue);
        }
    }

    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $ea
     * @param $fileEntity
     * @param array $fileConfig
     */
    private function upload(LifecycleEventArgs $ea, $fileEntity, array $fileConfig)
    {
        $propertyValue = $fileConfig['property']->getValue($fileEntity);
        if (!$propertyValue instanceof File) {
            return;
        }

        $mappedValue = $fileConfig['meta']->getFieldValue($fileEntity, $fileConfig['mappedBy']);
        $filename = basename($mappedValue);
        $path = dirname($mappedValue);

        $propertyValue->move($this->uploadDir . '/' .  $path, $filename);

        // Remove previous file
        $unitOfWork = $ea->getEntityManager()->getUnitOfWork();
        $changeSet = $unitOfWork->getEntityChangeSet($fileEntity);
        if (array_key_exists($fileConfig['mappedBy'], $changeSet)) {
            $oldvalue = $changeSet[$fileConfig['mappedBy']][0];
            if (null !== $oldvalue) {
                @unlink($this->uploadDir . '/' . $oldvalue);
            }
        }

        $fileConfig['property']->setValue($fileEntity, null);
    }

    /**
     * @param $fileEntity
     * @param array $fileConfig
     */
    private function removeUpload($fileEntity, array $fileConfig)
    {
        $mappedValue = $fileConfig['meta']->getFieldValue($fileEntity, $fileConfig['mappedBy']);
        if(null !== $mappedValue) {
            @unlink($this->uploadDir . '/' . $mappedValue);
        }
    }

    /**
     * @param $fileEntity
     * @param array $fileConfig
     * @return string
     */
    private function generateFileName($fileEntity, array $fileConfig)
    {
        return $fileConfig['path'] . '/' . uniqid() . '.' . $fileConfig['property']->getValue($fileEntity)->guessExtension();
    }
}