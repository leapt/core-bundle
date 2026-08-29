<?php

declare(strict_types=1);

use Leapt\CoreBundle\Listener\MessengerWorkerHeartbeatListener;
use Leapt\CoreBundle\Messenger\MessengerHelper;
use Leapt\CoreBundle\Twig\Extension\MessengerExtension;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Messenger\Event\WorkerRunningEvent;
use Symfony\Component\Messenger\Event\WorkerStartedEvent;
use Symfony\Component\Messenger\Event\WorkerStoppedEvent;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('leapt_core.messenger.cache')
            ->parent('cache.app')
            ->private()
            ->tag('cache.pool')

        ->set(MessengerHelper::class)
            ->arg('$cache', service('leapt_core.messenger.cache'))
            ->arg('$kernel', service('kernel'))

        ->set(MessengerWorkerHeartbeatListener::class)
            ->arg('$cache', service('leapt_core.messenger.cache'))
            ->tag('kernel.event_listener', ['event' => WorkerStartedEvent::class, 'method' => 'onWorkerStarted'])
            ->tag('kernel.event_listener', ['event' => WorkerRunningEvent::class, 'method' => 'onWorkerRunning'])
            ->tag('kernel.event_listener', ['event' => WorkerStoppedEvent::class, 'method' => 'onWorkerStopped'])

        ->set(MessengerExtension::class)
            ->arg('$messengerHelper', service(MessengerHelper::class))
            ->tag('twig.extension')
    ;
};
