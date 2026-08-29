<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Listener;

use Leapt\CoreBundle\Messenger\MessengerHelper;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Messenger\Event\WorkerRunningEvent;
use Symfony\Component\Messenger\Event\WorkerStartedEvent;
use Symfony\Component\Messenger\Event\WorkerStoppedEvent;
use Symfony\Component\Messenger\WorkerMetadata;

/**
 * Keeps a per-transport heartbeat in cache so that it's possible to tell whether a
 * `messenger:consume` worker is currently running, regardless of the transport type.
 */
final class MessengerWorkerHeartbeatListener
{
    private const int HEARTBEAT_TTL = 30;

    public function __construct(private readonly CacheItemPoolInterface $cache) {}

    public function onWorkerStarted(WorkerStartedEvent $event): void
    {
        $this->heartbeat($event->getWorker()->getMetadata());
    }

    public function onWorkerRunning(WorkerRunningEvent $event): void
    {
        $this->heartbeat($event->getWorker()->getMetadata());
    }

    public function onWorkerStopped(WorkerStoppedEvent $event): void
    {
        foreach ($event->getWorker()->getMetadata()->getTransportNames() as $transport) {
            $this->cache->deleteItem(MessengerHelper::CACHE_KEY_PREFIX . $transport);
        }
    }

    private function heartbeat(WorkerMetadata $metadata): void
    {
        foreach ($metadata->getTransportNames() as $transport) {
            $item = $this->cache->getItem(MessengerHelper::CACHE_KEY_PREFIX . $transport);
            $item->set(true);
            $item->expiresAfter(self::HEARTBEAT_TTL);
            $this->cache->save($item);
        }
    }
}
