<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\Twig\Extension;

use Leapt\CoreBundle\Twig\Extension\GoogleExtension;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class GoogleExtensionTest extends TestCase
{
    private Environment $env;

    protected function setUp(): void
    {
        $loader = new FilesystemLoader();
        $loader->addPath(__DIR__ . '/../../../templates', 'LeaptCore');
        $this->env = new Environment($loader);
    }

    public function testGetAnalyticsTrackingCode(): void
    {
        // Testing with an account id
        $extension = new GoogleExtension('phpunit_account_id');
        $extension->setDomainName('none');
        $extension->setAllowLinker('phpunit_allow_linker');

        // Testing DomainName and AllowLinker methods
        $this->assertEquals('none', $extension->getDomainName(), 'setDomainName: Should return "phpunit_domain_name"');
        $this->assertEquals('phpunit_allow_linker', $extension->getAllowLinker(), 'setAllowLinker: Should return "phpunit_allow_linker"');

        // Testing if all parameters are available in the template with domain name set to "none"
        $code = $extension->getAnalyticsTrackingCode($this->env);
        $this->assertStringContainsString($extension->getAccountId(), $code, sprintf('getAnalyticsTrackingCode: Should contain "%s"', $extension->getAccountId()));
        $this->assertStringContainsString($extension->getDomainName(), $code, sprintf('getAnalyticsTrackingCode: Should contain "%s"', $extension->getDomainName()));
        $this->assertStringContainsString($extension->getAllowLinker(), $code, sprintf('getAnalyticsTrackingCode: Should contain "%s"', $extension->getAllowLinker()));

        // Testing if domain name parameter is not available in the template with domain name set to "auto"
        $extension->setDomainName('auto');
        $this->assertStringNotContainsString(sprintf("_gaq.push(['_setDomainName', '{{ %s }}']);", 'auto'), $extension->getAnalyticsTrackingCode($this->env), 'getAnalyticsTrackingCode: Should not contain auto argument');

        // Testing without account id
        $extension = new GoogleExtension(null);
        $extension->setDomainName('none');
        $extension->setAllowLinker('phpunit_allow_linker');

        // Testing if all parameters except account id are available in the template when no account id is set
        $code = $extension->getAnalyticsTrackingCode($this->env);
        $this->assertStringContainsString($extension->getDomainName(), $code, sprintf('getAnalyticsTrackingCode: Should contain "%s"', $extension->getDomainName()));
        $this->assertStringContainsString($extension->getAllowLinker(), $code, sprintf('getAnalyticsTrackingCode: Should contain "%s"', $extension->getAllowLinker()));

        // Testing that a comment is set instead of tracking code when no account id is set and the domain name is set to "auto"
        $extension->setDomainName('auto');
        $this->assertEquals('<!-- AnalyticsTrackingCode: account id is null or domain name is not set to "none" -->', $extension->getAnalyticsTrackingCode($this->env), 'getAnalyticsTrackingCode: Should contain html comment with missing arguments');
    }
}
