<?php

namespace Snowcap\CoreBundle\Tests\Twig\Extension;

use Snowcap\CoreBundle\Twig\Extension\CoreExtension;

class CoreExtensionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var CoreExtension
     */
    private $extension;

    public function setUp()
    {
        $this->extension = new CoreExtension();
    }

    public function testIsActivePath()
    {
        $fakeRequest = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $fakeRequest->expects($this->any())->method('getRequestUri')->will($this->returnValue('some/request/uri'));

        $fakeContainer = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $fakeContainer->expects($this->any())->method('get')->with($this->equalTo('request'))->will($this->returnValue($fakeRequest));

        $this->extension->setContainer($fakeContainer);

        $this->extension->setActivePaths(array('some/specific/path'));
        $this->assertTrue($this->extension->isActivePath('some/specific/path'));
        $this->assertTrue($this->extension->isActivePath('some/request/uri'));
    }
}