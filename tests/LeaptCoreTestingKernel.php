<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests;

use Leapt\CoreBundle\LeaptCoreBundle;
use Leapt\CoreBundle\Tests\Feed\NewsFeed;
use Psr\Log\NullLogger;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

final class LeaptCoreTestingKernel extends Kernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        yield new FrameworkBundle();
        yield new TwigBundle();
        yield new LeaptCoreBundle();
    }

    private function configureContainer(ContainerConfigurator $container, LoaderInterface $loader, ContainerBuilder $builder): void
    {
        $container->extension('framework', [
            'secret' => 'S3CRET',
            'test'   => true,
        ]);
        $container->services()->set('logger', NullLogger::class);
        $container->services()->set(NewsFeed::class)
            ->tag('leapt_core.feed', ['alias' => 'news']);
    }

    private function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import(__DIR__ . '/../src/Resources/config/routing_feed.php')
            ->prefix('/feed');
    }
}
