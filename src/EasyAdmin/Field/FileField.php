<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\EasyAdmin\Field;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Leapt\CoreBundle\Form\Type\FileType;

final class FileField implements FieldInterface
{
    use FieldTrait;

    public const OPTION_ALLOW_DELETE = 'allowDelete';
    public const OPTION_ALLOW_DOWNLOAD = 'allowDownload';
    public const OPTION_FILE_PATH = 'filePath';

    public static function new(string $propertyName, ?string $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setTemplateName('crud/field/text')
            ->setFormType(FileType::class)
            ->setFormTypeOption('required', false)
            ->setCustomOption(self::OPTION_ALLOW_DELETE, true)
            ->setCustomOption(self::OPTION_ALLOW_DOWNLOAD, true)
            ->setCustomOption(self::OPTION_FILE_PATH, null)
            ->addFormTheme('@LeaptCore/Form/bootstrap_5_layout.html.twig')
        ;
    }

    public function setFilePath(string $path): self
    {
        $this->setCustomOption(self::OPTION_FILE_PATH, $path);

        return $this;
    }

    public function allowDelete(): self
    {
        $this->setCustomOption(self::OPTION_ALLOW_DELETE, true);

        return $this;
    }

    public function disallowDelete(): self
    {
        $this->setCustomOption(self::OPTION_ALLOW_DELETE, false);

        return $this;
    }

    public function allowDownload(): self
    {
        $this->setCustomOption(self::OPTION_ALLOW_DOWNLOAD, true);

        return $this;
    }

    public function disallowDownload(): self
    {
        $this->setCustomOption(self::OPTION_ALLOW_DOWNLOAD, false);

        return $this;
    }
}
