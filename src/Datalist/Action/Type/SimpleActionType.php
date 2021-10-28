<?php

namespace Leapt\CoreBundle\Datalist\Action\Type;

use Leapt\CoreBundle\Datalist\Action\DatalistActionInterface;
use Leapt\CoreBundle\Datalist\ViewContext;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class SimpleActionType.
 */
class SimpleActionType extends AbstractActionType
{
    protected RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

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
     * @param object $item
     *
     * @return string
     */
    public function getUrl(DatalistActionInterface $action, $item, array $options = [])
    {
        $parameters = [];
        $accessor = PropertyAccess::createPropertyAccessor();
        foreach ($options['params'] as $paramName => $paramPath) {
            $paramValue = $accessor->getValue($item, $paramPath);
            $parameters[$paramName] = $paramValue;
        }

        return $this->router->generate($options['route'], $parameters);
    }

    /**
     * @param $item
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
