<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Listener\Fixtures\Entity;

use Doctrine\ORM\Mapping as ORM;
use Leapt\CoreBundle\Doctrine\Mapping as LeaptORM;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\MappedSuperclass]
class Book
{
    #[ORM\Id, ORM\Column(type: 'integer'), ORM\GeneratedValue('AUTO')]
    protected int $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected ?string $title;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected ?string $attachment;

    #[LeaptORM\File(path: 'uploads/attachments', mappedBy: 'attachment')]
    protected ?File $attachmentFile;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setAttachment(?string $attachment): void
    {
        $this->attachment = $attachment;
    }

    public function getAttachment(): ?string
    {
        return $this->attachment;
    }

    public function setAttachmentFile(?File $attachmentFile): void
    {
        $this->attachmentFile = $attachmentFile;
    }

    public function getAttachmentFile(): ?File
    {
        return $this->attachmentFile;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }
}
