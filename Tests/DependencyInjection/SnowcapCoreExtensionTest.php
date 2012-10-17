<?php

namespace Snowcap\CoreBundle\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Snowcap\CoreBundle\DependencyInjection\SnowcapCoreExtension;

class SnowcapCoreExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testConfigLoad()
    {
        $extension = new SnowcapCoreExtension();

        $config = array();
        $container = new ContainerBuilder();
        $container->setParameter('kerner.root_dir', sys_get_temp_dir() . '/' . uniqid());
        $extension->load(array($config), $container);

        $this->assertInstanceOf('Snowcap\CoreBundle\Listener\FileSubscriber', $container->get('snowcap_core.file_subscriber'));
    }
}