<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Messenger;

use Leapt\CoreBundle\Messenger\MessengerHelper;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\EventListener\StopWorkerOnRestartSignalListener;

final class MessengerHelperTest extends KernelTestCase
{
    public function testCacheIsIsolatedFromCacheApp(): void
    {
        self::bootKernel();

        /** @var CacheItemPoolInterface $messengerCache */
        $messengerCache = self::getContainer()->get('leapt_core.messenger.cache');
        /** @var CacheItemPoolInterface $appCache */
        $appCache = self::getContainer()->get('cache.app');

        $item = $messengerCache->getItem(MessengerHelper::CACHE_KEY_PREFIX . 'async');
        $item->set(true);
        $messengerCache->save($item);

        self::assertFalse($appCache->getItem(MessengerHelper::CACHE_KEY_PREFIX . 'async')->isHit());
    }

    public function testIsWorkerRunningReturnsFalseWithoutHeartbeat(): void
    {
        $helper = new MessengerHelper(new ArrayAdapter(), $this->createStub(KernelInterface::class));

        self::assertFalse($helper->isWorkerRunning('async'));
    }

    public function testIsWorkerRunningReturnsTrueWithHeartbeat(): void
    {
        $cache = new ArrayAdapter();
        $item = $cache->getItem(MessengerHelper::CACHE_KEY_PREFIX . 'async');
        $item->set(true);
        $cache->save($item);

        $helper = new MessengerHelper($cache, $this->createStub(KernelInterface::class));

        self::assertTrue($helper->isWorkerRunning('async'));
    }

    public function testIsWorkerRunningIsIsolatedPerTransport(): void
    {
        $cache = new ArrayAdapter();
        $item = $cache->getItem(MessengerHelper::CACHE_KEY_PREFIX . 'async');
        $item->set(true);
        $cache->save($item);

        $helper = new MessengerHelper($cache, $this->createStub(KernelInterface::class));

        self::assertTrue($helper->isWorkerRunning('async'));
        self::assertFalse($helper->isWorkerRunning('other'));
    }

    public function testStopWorkersSendsRestartSignal(): void
    {
        self::bootKernel();

        $helper = new MessengerHelper(new ArrayAdapter(), self::$kernel);
        $helper->stopWorkers();

        /** @var CacheItemPoolInterface $restartSignalCache */
        $restartSignalCache = self::getContainer()->get('cache.messenger.restart_workers_signal');
        $item = $restartSignalCache->getItem(StopWorkerOnRestartSignalListener::RESTART_REQUESTED_TIMESTAMP_KEY);

        self::assertTrue($item->isHit());
        self::assertEqualsWithDelta(microtime(true), $item->get(), 5);
    }
}
