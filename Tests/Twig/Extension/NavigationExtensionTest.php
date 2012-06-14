<?php

namespace Snowcap\CoreBundle\Tests\Twig\Extension;

use Snowcap\CoreBundle\Twig\Extension\NavigationExtension;

class NavigationExtensionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var NavigationExtension
     */
    private $extension;

    public function setUp()
    {
        $this->extension = new NavigationExtension();
    }

    /**
     * Test isActivePath method
     */
    public function testIsActivePath()
    {
        $fakeRequest = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $fakeRequest->expects($this->any())->method('getRequestUri')->will($this->returnValue('some/request/uri'));

        $fakeContainer = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $fakeContainer->expects($this->any())->method('get')->with($this->equalTo('request'))->will($this->returnValue($fakeRequest));

        $this->extension->setContainer($fakeContainer);

        $this->extension->setActivePaths(array('some/specific/path'));
        $this->assertEquals(array('some/specific/path'), $this->extension->getActivePaths(), 'setActivePaths: Should return an array with "some/specific/path"');
        $this->assertTrue($this->extension->isActivePath('some/specific/path'), 'isActivePath: Should return true');
        $this->assertTrue($this->extension->isActivePath('some/request/uri'), 'isActivePath: Should return false');
    }
}