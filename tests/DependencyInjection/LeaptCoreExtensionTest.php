<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\Tests\DependencyInjection;

use Leapt\CoreBundle\DependencyInjection\LeaptCoreExtension;
use Leapt\CoreBundle\EasyAdmin\Field\Configurator\FileConfigurator;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

final class LeaptCoreExtensionTest extends AbstractExtensionTestCase
{
    public function testExtensionLoad(): void
    {
        $this->load();

        $this->assertContainerBuilderHasParameter('leapt_core.upload_dir', '%kernel.project_dir%/public');
        $this->assertContainerBuilderHasService(FileConfigurator::class);
    }

    public function testExtensionLoadWithFacebook(): void
    {
        $this->load();
        $this->assertContainerBuilderHasParameter('leapt_core.facebook.app_id', null);

        $this->load([
            'facebook' => ['app_id' => 123],
        ]);
        $this->assertContainerBuilderHasParameter('leapt_core.facebook.app_id', 123);
    }

    public function testExtensionLoadWithGoogle(): void
    {
        $this->load();
        $this->assertContainerBuilderHasParameter('leapt_core.google_analytics.tracking_id', null);
        $this->assertContainerBuilderHasParameter('leapt_core.google_analytics.domain_name', 'auto');
        $this->assertContainerBuilderHasParameter('leapt_core.google_analytics.allow_linker', false);
        $this->assertContainerBuilderHasParameter('leapt_core.google_analytics.debug', false);
        $this->assertContainerBuilderHasParameter('leapt_core.google_tags_manager.id', null);

        $this->load([
            'google_analytics' => [
                'tracking_id'  => 123,
                'domain_name'  => 'localhost',
                'allow_linker' => true,
                'debug'        => true,
            ],
            'google_tags_manager' => [
                'id' => 456,
            ],
        ]);
        $this->assertContainerBuilderHasParameter('leapt_core.google_analytics.tracking_id', 123);
        $this->assertContainerBuilderHasParameter('leapt_core.google_analytics.domain_name', 'localhost');
        $this->assertContainerBuilderHasParameter('leapt_core.google_analytics.allow_linker', true);
        $this->assertContainerBuilderHasParameter('leapt_core.google_analytics.debug', true);
        $this->assertContainerBuilderHasParameter('leapt_core.google_tags_manager.id', 456);
    }

    public function testExtensionLoadWithPaginator(): void
    {
        $this->load();
        $this->assertContainerBuilderHasParameter('leapt_core.paginator.template', '@LeaptCore/Paginator/paginator_default_layout.html.twig');

        $this->load([
            'paginator' => ['template' => '@LeaptCore/Paginator/paginator_bootstrap5_layout.html.twig'],
        ]);
        $this->assertContainerBuilderHasParameter('leapt_core.paginator.template', '@LeaptCore/Paginator/paginator_bootstrap5_layout.html.twig');
    }

    protected function getContainerExtensions(): array
    {
        return [
            new LeaptCoreExtension(),
        ];
    }
}
