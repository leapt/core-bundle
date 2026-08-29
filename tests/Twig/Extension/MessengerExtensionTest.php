<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Twig\Extension;

use Leapt\CoreBundle\Messenger\MessengerHelper;
use Leapt\CoreBundle\Twig\Extension\MessengerExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\HttpKernel\KernelInterface;

final class MessengerExtensionTest extends TestCase
{
    public function testIsMessengerWorkerRunning(): void
    {
        $cache = new ArrayAdapter();
        $item = $cache->getItem(MessengerHelper::CACHE_KEY_PREFIX . 'async');
        $item->set(true);
        $cache->save($item);

        $extension = new MessengerExtension(new MessengerHelper($cache, $this->createStub(KernelInterface::class)));

        self::assertTrue($extension->isMessengerWorkerRunning('async'));
        self::assertFalse($extension->isMessengerWorkerRunning('other'));
    }
}
