<?php

namespace Leapt\CoreBundle;

use Leapt\CoreBundle\DependencyInjection\Compiler\FeedCompilerPass;
use Leapt\CoreBundle\DependencyInjection\Compiler\SitemapCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class LeaptCoreBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new FeedCompilerPass());
        $container->addCompilerPass(new SitemapCompilerPass());
    }
}
