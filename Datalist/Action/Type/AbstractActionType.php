<?php

namespace Leapt\CoreBundle\Datalist\Action\Type;

use Leapt\CoreBundle\Datalist\Action\DatalistActionInterface;
use Leapt\CoreBundle\Datalist\ViewContext;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AbstractActionType
 * @package Leapt\CoreBundle\Datalist\Action\Type
 */
abstract class AbstractActionType implements ActionTypeInterface
{
    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(array(
                'attr'    => [],
                'enabled' => true,
            ))
            ->setAllowedTypes('enabled', ['bool', 'callable'])
        ;
    }

    /**
     * @param \Leapt\CoreBundle\Datalist\ViewContext $viewContext
     * @param \Leapt\CoreBundle\Datalist\Action\DatalistActionInterface $action
     * @param mixed $item
     * @param array $options
     */
    public function buildViewContext(ViewContext $viewContext, DatalistActionInterface $action, $item, array $options)
    {
        $viewContext['attr'] = $options['attr'];

        $enabled = $options['enabled'];
        if (is_callable($enabled)) {
            $enabled = call_user_func($enabled, $item);
        }
        if (!is_bool($enabled)) {
            throw new \UnexpectedValueException('The "enabled" callback must return a boolean value');
        }
        $viewContext['enabled'] = $enabled;

        $url = $action->getType()->getUrl($action, $item, $action->getOptions());

        $viewContext['url'] = $url;
        $viewContext['label'] = $action->getOption('label');
        $viewContext['translation_domain'] = $action->getDatalist()->getOption('translation_domain');
        $viewContext['options'] = $options;
        $viewContext['item'] = $item;
    }
}