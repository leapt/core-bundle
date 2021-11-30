<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Datalist\Action\Type;

use Leapt\CoreBundle\Datalist\Action\DatalistActionInterface;
use Leapt\CoreBundle\Datalist\ViewContext;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\RouterInterface;

class SimpleActionType extends AbstractActionType
{
    public function __construct(protected RouterInterface $router)
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults([
                'params' => [],
            ])
            ->setDefined(['icon'])
            ->setRequired(['route']);
    }

    public function getUrl(DatalistActionInterface $action, mixed $item, array $options = []): string
    {
        $parameters = [];
        $accessor = PropertyAccess::createPropertyAccessor();
        foreach ($options['params'] as $paramName => $paramPath) {
            $paramValue = $accessor->getValue($item, $paramPath);
            $parameters[$paramName] = $paramValue;
        }

        return $this->router->generate($options['route'], $parameters);
    }

    public function buildViewContext(ViewContext $viewContext, DatalistActionInterface $action, mixed $item, array $options): void
    {
        parent::buildViewContext($viewContext, $action, $item, $options);

        if (isset($options['icon'])) {
            $viewContext['icon'] = $options['icon'];
        }
    }

    public function getName(): string
    {
        return 'simple';
    }

    public function getBlockName(): string
    {
        return 'simple';
    }
}
