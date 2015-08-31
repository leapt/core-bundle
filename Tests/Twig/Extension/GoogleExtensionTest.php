<?php

namespace Leapt\CoreBundle\Tests\Twig\Extension;

use Leapt\CoreBundle\Twig\Extension\GoogleExtension;
use Symfony\Bridge\Twig\Tests\Extension\Fixtures\StubFilesystemLoader;

class GoogleExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Twig_Environment
     */
    private $env;

    /**
     * Setup Twig Environment for further uses
     */
    public function setUp()
    {
        if (!class_exists('Twig_Environment')) {
            $this->markTestSkipped('Twig is not available.');
        }

        $loader = new StubFilesystemLoader(array(
            __DIR__ . '/../../../Resources/views/Google',
            __DIR__,
        ));
        $this->env = new \Twig_Environment($loader);
    }

    /**
     * Test the GetAnalyticsTrackingCode method
     */
    public function testGetAnalyticsTrackingCode()
    {
        // Testing with an account id
        $extension = new GoogleExtension('phpunit_account_id');
        $extension->initRuntime($this->env);
        $extension->setDomainName('none');
        $extension->setAllowLinker('phpunit_allow_linker');

        // Testing DomainName and AllowLinker methods
        $this->assertEquals('none', $extension->getDomainName(), 'setDomainName: Should return "phpunit_domain_name"');
        $this->assertEquals('phpunit_allow_linker', $extension->getAllowLinker(), 'setAllowLinker: Should return "phpunit_allow_linker"');

        // Testing if all parameters are available in the template with domain name set to "none"
        $code = $extension->getAnalyticsTrackingCode();
        $this->assertContains($extension->getAccountId(), $code, sprintf('getAnalyticsTrackingCode: Should contain "%s"', $extension->getAccountId()));
        $this->assertContains($extension->getDomainName(), $code, sprintf('getAnalyticsTrackingCode: Should contain "%s"', $extension->getDomainName()));
        $this->assertContains($extension->getAllowLinker(), $code, sprintf('getAnalyticsTrackingCode: Should contain "%s"', $extension->getAllowLinker()));

        // Testing if domain name parameter is not available in the template with domain name set to "auto"
        $extension->setDomainName('auto');
        $this->assertNotContains(sprintf("_gaq.push(['_setDomainName', '{{ %s }}']);", 'auto'), $extension->getAnalyticsTrackingCode(), 'getAnalyticsTrackingCode: Should not contain auto argument');

        // Testing without account id
        $extension = new GoogleExtension(null);
        $extension->initRuntime($this->env);
        $extension->setDomainName('none');
        $extension->setAllowLinker('phpunit_allow_linker');

        // Testing if all parameters except account id are available in the template when no account id is set
        $code = $extension->getAnalyticsTrackingCode();
        $this->assertContains($extension->getDomainName(), $code, sprintf('getAnalyticsTrackingCode: Should contain "%s"', $extension->getDomainName()));
        $this->assertContains($extension->getAllowLinker(), $code, sprintf('getAnalyticsTrackingCode: Should contain "%s"', $extension->getAllowLinker()));

        // Testing that a comment is set instead of tracking code when no account id is set and the domain name is set to "auto"
        $extension->setDomainName('auto');
        $this->assertEquals('<!-- AnalyticsTrackingCode: account id is null or domain name is not set to "none" -->', $extension->getAnalyticsTrackingCode(), 'getAnalyticsTrackingCode: Should contain html comment with missing arguments');
    }
}