<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Paginator\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Player
{
    #[ORM\Id, ORM\Column(type: 'integer'), ORM\GeneratedValue('AUTO')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $firstName;

    #[ORM\Column(type: 'string', length: 255)]
    private string $lastName;

    public function getId(): int
    {
        return $this->id;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }
}
