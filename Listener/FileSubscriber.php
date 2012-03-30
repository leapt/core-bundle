<?php

namespace Snowcap\CoreBundle\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;

class FileSubscriber implements EventSubscriber
{
    private $config;

    /**
     * @var string
     */
    private $rootDir;
    /**
     * @param string $rootDir
     */
    public function __construct($rootDir){
        $this->rootDir = $rootDir;
    }
    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array('prePersist', 'postPersist', 'postUpdate', 'postRemove','loadClassMetadata','preFlush');
    }

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
                $field = $property->getName();
                $this->config[$meta->getTableName()]['fields'][$field] = array(
                    'property' => $property,
                    'path' => $annotation->path,
                    'mappedBy' => $annotation->mappedBy,
                );
            }
        }
    }

    public function preFlush(\Doctrine\ORM\Event\PreFlushEventArgs $ea)
    {
        /** @var $unitOfWork \Doctrine\ORM\UnitOfWork */
        $unitOfWork = $ea->getEntityManager()->getUnitOfWork();

        $entityMaps = $unitOfWork->getIdentityMap();
        foreach($entityMaps as $entities) {
            foreach($entities as $entity) {
                foreach($this->getFiles($entity,$ea->getEntityManager()) as $file) {
                    $this->preUpload($ea, $entity,$file);
                }
            }
        }
    }

    private function getFiles($entity, \Doctrine\ORM\EntityManager $entityManager)
    {
        $classMetaData = $entityManager->getClassMetaData(get_class($entity));
        $tableName = $classMetaData->getTableName();

        if(array_key_exists($tableName, $this->config)) {
            return $this->config[$tableName]['fields'];
        } else {
            return array();
        }
    }

    public function prePersist(LifecycleEventArgs $ea)
    {
        $entity = $ea->getEntity();
        foreach($this->getFiles($entity,$ea->getEntityManager()) as $file) {
            $this->preUpload($ea, $entity,$file);
        }
    }

    public function postPersist(LifecycleEventArgs $ea)
    {
        $entity = $ea->getEntity();
        foreach($this->getFiles($entity,$ea->getEntityManager()) as $file) {
            $this->upload($ea, $entity,$file);
        }
    }

    public function postUpdate(LifecycleEventArgs $ea)
    {
        $entity = $ea->getEntity();
        foreach($this->getFiles($entity,$ea->getEntityManager()) as $file) {
            $this->upload($ea, $entity,$file);
        }
    }

    public function postRemove(LifecycleEventArgs $ea)
    {
        $entity = $ea->getEntity();
        foreach($this->getFiles($entity,$ea->getEntityManager()) as $file) {
            $this->removeUpload($entity,$file);
        }
    }

    private function preUpload($ea, $fileEntity, $file)
    {
        $propertyName = $file['property']->name;
        if (null !== $fileEntity->$propertyName) {
            $getter = "get" . ucfirst(strtolower($file['mappedBy']));
            $setter = "set" . ucfirst(strtolower($file['mappedBy']));
            $oldValue = $fileEntity->$getter();
            $newValue = $file['path'] . '/' . uniqid() . '.' . $fileEntity->$propertyName->guessExtension();
            $fileEntity->$setter($newValue);
            /** @var $entityManager \Doctrine\ORM\EntityManager */
            $entityManager = $ea->getEntityManager();
            $entityManager->getUnitOfWork()->propertyChanged($fileEntity, $file['mappedBy'], $oldValue, $newValue);
        }
    }

    private function upload(LifecycleEventArgs $ea, $fileEntity, $file)
    {
        $propertyName = $file['property']->name;
        if (null === $fileEntity->$propertyName) {
            return;
        }

        $getter = "get" . ucfirst(strtolower($file['mappedBy']));
        $filename = basename($fileEntity->$getter());
        $path = dirname($fileEntity->$getter());

        $fileEntity->$propertyName->move($this->getUploadRootDir() . $path, $filename);


        // Remove previous file
        $unitOfWork = $ea->getEntityManager()->getUnitOfWork();
        $changeSet = $unitOfWork->getEntityChangeSet($fileEntity);
        if(array_key_exists($file['mappedBy'],$changeSet)) {
            $oldvalue = $changeSet[$file['mappedBy']][0];
            if($oldvalue != '' && $oldvalue != NULL) {
                unlink($this->getUploadRootDir($fileEntity) . '/' . $oldvalue);
            }
        }

        unset($fileEntity->$propertyName);
    }

    private function removeUpload($fileEntity, $file)
    {
        if ($file['path'] != "") {
            $getter = "get" . ucfirst(strtolower($file['mappedBy']));
            $filePath = $fileEntity->$getter();
            if($filePath != "") {
                unlink($this->getUploadRootDir($fileEntity) . '/' . $fileEntity->$getter());
            }
        }
    }

    private function getUploadRootDir()
    {
        // the absolute directory path where uploaded documents should be saved
        return $this->rootDir . '/../web/';
    }
}