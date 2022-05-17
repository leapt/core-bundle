<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\DependencyInjection;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class ConfigurationTest extends KernelTestCase
{
    public function testLoad(): void
    {
        // Make sure default config loads without issues
        self::bootKernel();
        self::assertSame(
            '@LeaptCore/Paginator/paginator_default_layout.html.twig',
            self::getContainer()->getParameter('leapt_core.paginator.template'),
        );
    }
}
