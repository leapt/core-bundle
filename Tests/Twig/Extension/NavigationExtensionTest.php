<?php

namespace Leapt\CoreBundle\Tests\Twig\Extension;

use Leapt\CoreBundle\Navigation\NavigationRegistry;
use Leapt\CoreBundle\Twig\Extension\NavigationExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class NavigationExtensionTest extends TestCase
{
    /**
     * @var NavigationExtension
     */
    private $extension;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function setUp()
    {
        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $this->request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $registry = new NavigationRegistry();
        $registry->setContainer($this->container);

        $this->extension = new NavigationExtension($registry);
    }

    /**
     * Test isActivePath method
     */
    public function testIsActivePath()
    {
        $this->request->expects($this->any())->method('getRequestUri')->will($this->returnValue('some/request/uri'));
        $this->container->expects($this->any())->method('get')->with($this->equalTo('request'))->will($this->returnValue($this->request));

        $this->extension->setActivePaths(array('some/specific/path'));
        $this->assertEquals(array('some/specific/path'), $this->extension->getActivePaths(), 'setActivePaths: Should return an array with "some/specific/path"');
        $this->assertTrue($this->extension->isActivePath('some/specific/path'), 'isActivePath: Should return true');
        $this->assertTrue($this->extension->isActivePath('some/request/uri'), 'isActivePath: Should return false');
    }
}
