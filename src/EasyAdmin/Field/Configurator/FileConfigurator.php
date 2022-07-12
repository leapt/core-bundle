<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\EasyAdmin\Field\Configurator;

use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldConfiguratorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use Leapt\CoreBundle\EasyAdmin\Field\FileField;
use Leapt\CoreBundle\EasyAdmin\Field\ImageField;
use Symfony\Bridge\Twig\Extension\AssetExtension;
use Symfony\Component\PropertyAccess\PropertyAccess;

final class FileConfigurator implements FieldConfiguratorInterface
{
    public function __construct(private AssetExtension $assetExtension)
    {
    }

    public function supports(FieldDto $field, EntityDto $entityDto): bool
    {
        return \in_array($field->getFieldFqcn(), [FileField::class, ImageField::class], true);
    }

    public function configure(FieldDto $field, EntityDto $entityDto, AdminContext $context): void
    {
        if (null === $filePath = $field->getCustomOption(ImageField::OPTION_FILE_PATH)) {
            throw new \RuntimeException(sprintf('The "%s" field must define the filePath using the "setFilePath()" method.', $field->getProperty()));
        }

        $field->setFormTypeOption('file_path', $filePath);
        $field->setFormTypeOption('allow_delete', $field->getCustomOption(ImageField::OPTION_ALLOW_DELETE));
        $field->setFormTypeOption('allow_download', $field->getCustomOption(ImageField::OPTION_ALLOW_DOWNLOAD));

        $formattedValue = null;
        try {
            if (\is_callable($filePath)) {
                $formattedValue = \call_user_func($filePath, $entityDto->getInstance());
            } else {
                $accessor = PropertyAccess::createPropertyAccessor();
                $fileUrl = $accessor->getValue($entityDto->getInstance(), $filePath);
                $formattedValue = $this->assetExtension->getAssetUrl($fileUrl);
            }
        } catch (\Exception) {
        }
        $field->setFormattedValue($formattedValue);
    }
}
