<?php

declare(strict_types=1);

use Leapt\CoreBundle\EasyAdmin\Field\Configurator\FileConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set(FileConfigurator::class)
            ->arg('$assetExtension', service('twig.extension.assets'))
            ->tag('ea.field_configurator')
    ;
};
