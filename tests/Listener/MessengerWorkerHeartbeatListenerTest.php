<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Listener;

use Leapt\CoreBundle\Listener\MessengerWorkerHeartbeatListener;
use Leapt\CoreBundle\Messenger\MessengerHelper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Messenger\Event\WorkerRunningEvent;
use Symfony\Component\Messenger\Event\WorkerStartedEvent;
use Symfony\Component\Messenger\Event\WorkerStoppedEvent;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Transport\Receiver\ReceiverInterface;
use Symfony\Component\Messenger\Worker;

final class MessengerWorkerHeartbeatListenerTest extends TestCase
{
    private ArrayAdapter $cache;
    private MessengerWorkerHeartbeatListener $listener;

    protected function setUp(): void
    {
        $this->cache = new ArrayAdapter();
        $this->listener = new MessengerWorkerHeartbeatListener($this->cache);
    }

    public function testOnWorkerStartedSetsHeartbeatForEachTransport(): void
    {
        $worker = $this->createWorker('async', 'other');

        $this->listener->onWorkerStarted(new WorkerStartedEvent($worker));

        self::assertTrue($this->isHeartbeatSet('async'));
        self::assertTrue($this->isHeartbeatSet('other'));
    }

    public function testOnWorkerRunningSetsHeartbeat(): void
    {
        $worker = $this->createWorker('async');

        $this->listener->onWorkerRunning(new WorkerRunningEvent($worker, true));

        self::assertTrue($this->isHeartbeatSet('async'));
    }

    public function testOnWorkerStoppedRemovesHeartbeatForEachTransport(): void
    {
        $worker = $this->createWorker('async', 'other');

        $this->listener->onWorkerStarted(new WorkerStartedEvent($worker));
        $this->listener->onWorkerStopped(new WorkerStoppedEvent($worker));

        self::assertFalse($this->isHeartbeatSet('async'));
        self::assertFalse($this->isHeartbeatSet('other'));
    }

    public function testOnWorkerStoppedDoesNotAffectOtherWorkersTransports(): void
    {
        $asyncWorker = $this->createWorker('async');
        $otherWorker = $this->createWorker('other');

        $this->listener->onWorkerStarted(new WorkerStartedEvent($asyncWorker));
        $this->listener->onWorkerStarted(new WorkerStartedEvent($otherWorker));

        $this->listener->onWorkerStopped(new WorkerStoppedEvent($asyncWorker));

        self::assertFalse($this->isHeartbeatSet('async'));
        self::assertTrue($this->isHeartbeatSet('other'));
    }

    private function createWorker(string ...$transportNames): Worker
    {
        $receivers = [];
        foreach ($transportNames as $transportName) {
            $receivers[$transportName] = $this->createStub(ReceiverInterface::class);
        }

        return new Worker($receivers, $this->createStub(MessageBusInterface::class));
    }

    private function isHeartbeatSet(string $transport): bool
    {
        return $this->cache->getItem(MessengerHelper::CACHE_KEY_PREFIX . $transport)->isHit();
    }
}
