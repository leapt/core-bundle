<?php

namespace Snowcap\CoreBundle\Tests\Listener\Fixtures\Entity;

use Doctrine\ORM\Mapping as ORM;
use Snowcap\CoreBundle\Doctrine\Mapping as SnowcapORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\MappedSuperClass
 */
class Book {
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=true)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="attachment", type="string", length=255, nullable=true)
     */
    protected $attachment;

    /**
     * @var \Symfony\Component\HttpFoundation\File\File
     *
     * @SnowcapORM\File(path="uploads/attachments", mappedBy="attachment")
     */
    protected $attachmentFile;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $cv
     */
    public function setAttachment($cv)
    {
        $this->attachment = $cv;
    }

    /**
     * @return string
     */
    public function getAttachment()
    {
        return $this->attachment;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\File $cvFile
     */
    public function setAttachmentFile($cvFile)
    {
        $this->attachmentFile = $cvFile;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    public function getAttachmentFile()
    {
        return $this->attachmentFile;
    }

    /**
     * @param string $name
     */
    public function setTitle($name)
    {
        $this->title = $name;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

}