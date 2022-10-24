<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class SitemapControllerTest extends WebTestCase
{
    private static KernelBrowser $client;

    protected function setUp(): void
    {
        self::$client = self::createClient();
    }

    public function testValidSitemapName(): void
    {
        $crawler = self::$client->request('GET', '/sitemap.xml');
        self::assertResponseIsSuccessful();
        self::assertStringContainsString('<loc>http://localhost/sitemap_first.xml</loc>', $crawler->html());
        self::assertStringContainsString('<loc>http://localhost/sitemap_second.xml</loc>', $crawler->html());

        self::$client->request('GET', '/sitemap_first.xml');
        self::assertResponseIsSuccessful();
        self::$client->request('GET', '/sitemap_second.xml');
        self::assertResponseIsSuccessful();
    }

    public function testInvalidFeedName(): void
    {
        self::$client->request('GET', '/sitemap_unknown.xml');
        self::assertResponseStatusCodeSame(404);
    }
}
