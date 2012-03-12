<?php

namespace Snowcap\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

abstract class File
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length="255")
     */
    protected $path;

    /**
     * @Assert\File(maxSize="6000000")
     * @Assert\NotBlank()
     */
    public $file;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return null|string
     */
    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir() . '/' . $this->path;
    }

    /**
     * @abstract
     *
     * @return string
     */
    abstract public function getUploadDir();
}