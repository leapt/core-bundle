<?php

declare(strict_types=1);

namespace Leapt\CoreBundle;

use Leapt\CoreBundle\DependencyInjection\Compiler\DatalistCompilerPass;
use Leapt\CoreBundle\DependencyInjection\Compiler\FeedCompilerPass;
use Leapt\CoreBundle\DependencyInjection\Compiler\FlysystemCompilerPass;
use Leapt\CoreBundle\DependencyInjection\Compiler\SitemapCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class LeaptCoreBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new DatalistCompilerPass());
        $container->addCompilerPass(new FeedCompilerPass());
        $container->addCompilerPass(new FlysystemCompilerPass());
        $container->addCompilerPass(new SitemapCompilerPass());
    }
}
