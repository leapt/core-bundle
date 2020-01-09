<?php

namespace Leapt\CoreBundle\Datalist\Action\Type;

use Leapt\CoreBundle\Datalist\Action\DatalistActionInterface;
use Leapt\CoreBundle\Datalist\ViewContext;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class SimpleActionType
 * @package Leapt\CoreBundle\Datalist\Action\Type
 */
class SimpleActionType extends AbstractActionType
{
    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    protected $router;

    /**
     * @param \Symfony\Component\Routing\RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults([
                'params' => [],
            ])
            ->setDefined(['icon'])
            ->setRequired(['route']);
    }

    /**
     * @param \Leapt\CoreBundle\Datalist\Action\DatalistActionInterface $action
     * @param object $item
     * @param array $options
     * @return string
     */
    public function getUrl(DatalistActionInterface $action, $item, array $options = [])
    {
        $parameters = [];
        $accessor = PropertyAccess::createPropertyAccessor();
        foreach($options['params'] as $paramName => $paramPath) {
            $paramValue = $accessor->getValue($item, $paramPath);
            $parameters[$paramName] = $paramValue;
        }

        return $this->router->generate($options['route'], $parameters);
    }

    /**
     * @param \Leapt\CoreBundle\Datalist\ViewContext $viewContext
     * @param \Leapt\CoreBundle\Datalist\Action\DatalistActionInterface $action
     * @param $item
     * @param array $options
     */
    public function buildViewContext(ViewContext $viewContext, DatalistActionInterface $action, $item, array $options)
    {
        parent::buildViewContext($viewContext, $action, $item, $options);

        if (isset($options['icon'])) {
            $viewContext['icon'] = $options['icon'];
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'simple';
    }

    /**
     * @return string
     */
    public function getBlockName()
    {
        return 'simple';
    }
}