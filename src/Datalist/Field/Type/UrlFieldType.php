<?php

namespace Leapt\CoreBundle\Datalist\Field\Type;

use Leapt\CoreBundle\Datalist\Field\DatalistFieldInterface;
use Leapt\CoreBundle\Datalist\ViewContext;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Add a link surrounding the TextFieldType.
 */
class UrlFieldType extends TextFieldType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefined(['url'])
            ->setAllowedTypes('url', ['callable', 'string'])
        ;
    }

    public function buildViewContext(ViewContext $viewContext, DatalistFieldInterface $field, mixed $value, array $options): void
    {
        parent::buildViewContext($viewContext, $field, $value, $options);

        $url = $field->getOption('url');

        if (\is_callable($url)) {
            $url = \call_user_func($url, $value);
        }

        $viewContext['url'] = $url;
    }

    public function getName(): string
    {
        return 'url';
    }

    public function getBlockName(): string
    {
        return 'url';
    }
}
