<?php

declare(strict_types=1);

use Leapt\CoreBundle\Form\Type\FileType;
use Leapt\CoreBundle\Form\Extension\CollectionTypeExtension;
use Leapt\CoreBundle\Listener\FileSubscriber;
use Leapt\CoreBundle\Navigation\NavigationRegistry;
use Leapt\CoreBundle\Twig\Extension\NavigationExtension;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $container->services()
        // Form types
        ->set('leapt_core.file_type')
            ->class(FileType::class)
            ->tag('form.type')
            ->call('setUploadDir', ['%leapt_core.upload_dir%'])

        // Form type extensions
        ->set('leapt_core.collection_type_extension')
            ->class(CollectionTypeExtension::class)
            ->tag('form.type_extension')

        // Field entity event subscriber
        ->set('leapt_core.file_subscriber')
            ->class(FileSubscriber::class)
            ->tag('doctrine.event_subscriber')

        // Navigation
        ->set('leapt_core.navigation')
            ->class(NavigationRegistry::class)
            ->arg(0, service('request_stack'))

        ->set('leapt_core.twig_navigation')
            ->class(NavigationExtension::class)
            ->arg(0, service('leapt_core.navigation'))
            ->tag('twig.extension')
    ;
};
