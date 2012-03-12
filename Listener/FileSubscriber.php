<?php

namespace Snowcap\CoreBundle\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Snowcap\CoreBundle\Entity\File;

class FileSubscriber implements EventSubscriber
{
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
        return array('prePersist', 'preUpdate', 'postPersist', 'postUpdate', 'postRemove');
    }

    public function prePersist(LifecycleEventArgs $ea)
    {
        $entity = $ea->getEntity();
        if ($entity instanceof File) {
            $this->preUpload($entity);
        }
    }

    public function preUpdate(LifecycleEventArgs $ea)
    {
        $entity = $ea->getEntity();
        if ($entity instanceof File) {
            $this->preUpload($entity);
        }
    }

    public function postPersist(LifecycleEventArgs $ea)
    {
        $entity = $ea->getEntity();
        if ($entity instanceof File) {
            $this->upload($entity);
        }
    }

    public function postUpdate(LifecycleEventArgs $ea)
    {
        $entity = $ea->getEntity();
        if ($entity instanceof File) {
            $this->upload($entity);
        }
    }

    public function postRemove(LifecycleEventArgs $ea)
    {
        $entity = $ea->getEntity();
        if ($entity instanceof File) {
            $this->removeUpload($entity);
        }
    }


    private function preUpload(File $fileEntity)
    {
        if (null !== $fileEntity->file) {
            // do whatever you want to generate a unique name
            $fileEntity->setPath(uniqid() . '.' . $fileEntity->file->guessExtension());
        }
    }

    private function upload(File $fileEntity)
    {
        if (null === $fileEntity->file) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $fileEntity->file->move($this->getUploadRootDir($fileEntity), $fileEntity->getPath());

        unset($fileEntity->file);
    }

    /**
     * @ORM\PostRemove()
     */
    private function removeUpload(File $fileEntity)
    {
        if ($file = $this->getAbsolutePath($fileEntity)) {
            unlink($file);
        }
    }

    private function getUploadRootDir(File $fileEntity)
    {
        // the absolute directory path where uploaded documents should be saved
        return $this->rootDir . '/../web/' . $fileEntity->getUploadDir();
    }

    private function getAbsolutePath(File $fileEntity)
    {
        return null === $fileEntity->getPath() ? null : $this->getUploadRootDir($fileEntity) . '/' . $fileEntity->getPath();
    }


}