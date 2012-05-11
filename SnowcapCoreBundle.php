<?php

namespace Snowcap\CoreBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Snowcap\CoreBundle\DependencyInjection\Compiler\FeedCompilerPass;

class SnowcapCoreBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new FeedCompilerPass());
    }

}
