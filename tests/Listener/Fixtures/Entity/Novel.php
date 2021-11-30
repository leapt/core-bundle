<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Listener\Fixtures\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Novel extends Book
{
    #[ORM\Column(type: 'string', length: 255)]
    private string $subtitle;

    public function setSubtitle(string $subtitle): void
    {
        $this->subtitle = $subtitle;
    }

    public function getSubtitle(): string
    {
        return $this->subtitle;
    }
}
