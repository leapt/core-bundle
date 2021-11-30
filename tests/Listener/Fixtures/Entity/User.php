<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Listener\Fixtures\Entity;

use Doctrine\ORM\Mapping as ORM;
use Leapt\CoreBundle\Doctrine\Mapping as LeaptORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=true)
     */
    private $userName;

    /**
     * @var string
     *
     * @ORM\Column(name="cv", type="string", length=255, nullable=true)
     */
    private $cv;

    /**
     * @var \Symfony\Component\HttpFoundation\File\File
     *
     * @LeaptORM\File(path="uploads/cvs", mappedBy="cv")
     */
    private $cvFile;

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
    public function setCv($cv)
    {
        $this->cv = $cv;
    }

    /**
     * @return string
     */
    public function getCv()
    {
        return $this->cv;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\File $cvFile
     */
    public function setCvFile($cvFile)
    {
        $this->cvFile = $cvFile;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    public function getCvFile()
    {
        return $this->cvFile;
    }

    /**
     * @param string $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }
}
