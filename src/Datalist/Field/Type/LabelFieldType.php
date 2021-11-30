<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Datalist\Field\Type;

use Leapt\CoreBundle\Datalist\Field\DatalistFieldInterface;
use Leapt\CoreBundle\Datalist\ViewContext;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LabelFieldType extends AbstractFieldType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setRequired(['mappings'])
            ->setAllowedTypes('mappings', 'array')
        ;
    }

    /**
     * @throws \UnexpectedValueException
     * @throws \Exception
     */
    public function buildViewContext(ViewContext $viewContext, DatalistFieldInterface $field, mixed $value, array $options): void
    {
        parent::buildViewContext($viewContext, $field, $value, $options);

        $mappings = $options['mappings'];

        // Convert boolean value to integer to avoid problem with indexed arrays
        if (\is_bool($viewContext['value'])) {
            $viewContext['value'] = (int) $viewContext['value'];
        }
        if (!\array_key_exists($viewContext['value'], $mappings)) {
            throw new \UnexpectedValueException(sprintf('No mapping for value %s', $viewContext['value']));
        }

        $mapping = $mappings[$viewContext['value']];
        if (!\is_array($mapping)) {
            throw new \Exception('mappings for the label field type must be specified as an associative array');
        }

        $viewContext['attr'] = isset($mapping['attr']) ? $mapping['attr'] : [];
        $viewContext['value'] = $mapping['label'];
    }

    public function getName(): string
    {
        return 'label';
    }

    public function getBlockName(): string
    {
        return 'label';
    }
}
