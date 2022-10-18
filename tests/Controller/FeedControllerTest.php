<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class FeedControllerTest extends WebTestCase
{
    private static KernelBrowser $client;

    protected function setUp(): void
    {
        self::$client = self::createClient();
    }

    public function testValidFeedName(): void
    {
        self::$client->request('GET', '/feed/news');
        self::assertResponseIsSuccessful();
        self::$client->request('GET', '/feed/news.atom');
        self::assertResponseIsSuccessful();
    }

    public function testInvalidFeedName(): void
    {
        self::$client->request('GET', '/feed/unknown');
        self::assertResponseStatusCodeSame(500);
        self::$client->request('GET', '/feed/unknown.atom');
        self::assertResponseStatusCodeSame(500);
    }
}
