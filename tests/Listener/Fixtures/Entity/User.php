<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Listener\Fixtures\Entity;

use Doctrine\ORM\Mapping as ORM;
use Leapt\CoreBundle\Doctrine\Mapping as LeaptORM;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity]
class User
{
    #[ORM\Id, ORM\Column(type: 'integer'), ORM\GeneratedValue('AUTO')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $userName;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $cv;

    #[LeaptORM\File(path: 'uploads/cvs', mappedBy: 'cv')]
    private ?File $cvFile;

    public function getId(): int
    {
        return $this->id;
    }

    public function setCv(?string $cv): void
    {
        $this->cv = $cv;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCvFile(?File $cvFile): void
    {
        $this->cvFile = $cvFile;
    }

    public function getCvFile(): ?File
    {
        return $this->cvFile;
    }

    public function setUserName(?string $userName): void
    {
        $this->userName = $userName;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }
}
