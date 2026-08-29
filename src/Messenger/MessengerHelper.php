<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Messenger;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpKernel\KernelInterface;

final class MessengerHelper
{
    public const string CACHE_KEY_PREFIX = 'messenger_worker_heartbeat.';

    public function __construct(
        private readonly CacheItemPoolInterface $cache,
        private readonly KernelInterface $kernel,
    ) {}

    public function isWorkerRunning(string $transport): bool
    {
        return $this->cache->getItem(self::CACHE_KEY_PREFIX . $transport)->isHit();
    }

    public function stopWorkers(): void
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);
        $input = new ArrayInput(['command' => 'messenger:stop-workers']);
        $application->run($input, new NullOutput());
    }
}
