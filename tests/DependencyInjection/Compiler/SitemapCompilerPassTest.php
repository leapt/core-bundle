<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\DependencyInjection\Compiler;

use Leapt\CoreBundle\DependencyInjection\Compiler\SitemapCompilerPass;
use Leapt\CoreBundle\Sitemap\AbstractSitemap;
use Leapt\CoreBundle\Sitemap\SitemapManager;
use Leapt\CoreBundle\Tests\Sitemap\FirstSitemap;
use Leapt\CoreBundle\Tests\Sitemap\SecondSitemap;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class SitemapCompilerPassTest extends TestCase
{
    public function testAutoconfiguredSitemapsAreRegisteredWithoutAnExplicitTag(): void
    {
        $container = new ContainerBuilder();
        $container->register(SitemapManager::class, SitemapManager::class)->setPublic(true);
        $container->registerForAutoconfiguration(AbstractSitemap::class)->addTag('leapt_core.sitemap');

        $container->register(FirstSitemap::class, FirstSitemap::class)
            ->setAutoconfigured(true)
            ->setPublic(true);
        $container->register(SecondSitemap::class, SecondSitemap::class)
            ->setAutoconfigured(true)
            ->setPublic(true)
            ->addTag('leapt_core.sitemap', ['alias' => 'second']);

        $container->addCompilerPass(new SitemapCompilerPass());
        $container->compile();

        $methodCalls = $container->getDefinition(SitemapManager::class)->getMethodCalls();

        self::assertSame([
            ['registerSitemap', [FirstSitemap::class, FirstSitemap::class]],
            ['registerSitemap', ['second', SecondSitemap::class]],
        ], array_map(
            static fn(array $call) => [$call[0], [$call[1][0], (string) $call[1][1]]],
            $methodCalls,
        ));
    }

    public function testCompilerPassDoesNothingWithoutSitemapManagerDefinition(): void
    {
        $container = new ContainerBuilder();
        $container->register(FirstSitemap::class, FirstSitemap::class)
            ->addTag('leapt_core.sitemap');

        new SitemapCompilerPass()->process($container);

        self::assertFalse($container->hasDefinition(SitemapManager::class));
    }
}
