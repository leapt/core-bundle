<?php

namespace Leapt\CoreBundle\Tests\Twig\Extension;

use Leapt\CoreBundle\Navigation\NavigationRegistry;
use Leapt\CoreBundle\Twig\Extension\NavigationExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class NavigationExtensionTest extends TestCase
{
    /**
     * @var NavigationExtension
     */
    private $extension;

    public function setUp()
    {
        $requestStack = new RequestStack();
        $request = Request::create('some/request/uri');
        $requestStack->push($request);
        $registry = new NavigationRegistry($requestStack);

        $this->extension = new NavigationExtension($registry);
    }

    /**
     * Test isActivePath method
     */
    public function testIsActivePath()
    {
        $this->extension->setActivePaths(['some/specific/path']);
        $this->assertEquals(['some/specific/path'], $this->extension->getActivePaths(), 'setActivePaths: Should return an array with "some/specific/path"');
        $this->assertTrue($this->extension->isActivePath('some/specific/path'), 'isActivePath: Should return true');
        $this->assertTrue($this->extension->isActivePath('some/request/uri'), 'isActivePath: Should return true');
    }
}
