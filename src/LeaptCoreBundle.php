<?php

declare(strict_types=1);

namespace Leapt\CoreBundle;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldConfiguratorInterface;
use Leapt\CoreBundle\Datalist\Action\Type\ActionTypeInterface;
use Leapt\CoreBundle\Datalist\Field\Type\FieldTypeInterface;
use Leapt\CoreBundle\Datalist\Filter\Type\FilterTypeInterface;
use Leapt\CoreBundle\Datalist\Type\DatalistTypeInterface;
use Leapt\CoreBundle\DependencyInjection\Compiler\DatalistCompilerPass;
use Leapt\CoreBundle\DependencyInjection\Compiler\FeedCompilerPass;
use Leapt\CoreBundle\DependencyInjection\Compiler\FlysystemCompilerPass;
use Leapt\CoreBundle\DependencyInjection\Compiler\SitemapCompilerPass;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class LeaptCoreBundle extends AbstractBundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new DatalistCompilerPass());
        $container->addCompilerPass(new FeedCompilerPass());
        $container->addCompilerPass(new FlysystemCompilerPass());
        $container->addCompilerPass(new SitemapCompilerPass());
    }

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->import('../config/definition.php');
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.php');

        $this->configureDatalist($builder);
        $this->configureEasyAdmin($container);
        $this->configureFacebook($builder, $config);
        $this->configureGoogle($builder, $config);
        $this->configureHoneypot($builder, $config);
        $this->configurePaginator($builder, $config);
        $this->configureRecaptcha($builder, $config);
        $this->configureUploads($builder, $config);
    }

    private function configureDatalist(ContainerBuilder $builder): void
    {
        // Auto-register Datalist types
        $builder->registerForAutoconfiguration(ActionTypeInterface::class)
            ->addTag('leapt_core.datalist.action_type');
        $builder->registerForAutoconfiguration(FieldTypeInterface::class)
            ->addTag('leapt_core.datalist.field_type');
        $builder->registerForAutoconfiguration(FilterTypeInterface::class)
            ->addTag('leapt_core.datalist.filter_type');
        $builder->registerForAutoconfiguration(DatalistTypeInterface::class)
            ->addTag('leapt_core.datalist.type');
    }

    private function configureEasyAdmin(ContainerConfigurator $container): void
    {
        if (interface_exists(FieldConfiguratorInterface::class)) {
            $container->import('../config/services_easyadmin.php');
        }
    }

    private function configureFacebook(ContainerBuilder $builder, array $config): void
    {
        if (isset($config['facebook'])) {
            foreach (['app_id'] as $option) {
                $builder->setParameter('leapt_core.facebook.' . $option, $config['facebook'][$option]);
            }
        }
    }

    private function configureGoogle(ContainerBuilder $builder, array $config): void
    {
        if (isset($config['google_analytics'])) {
            foreach (['tracking_id', 'domain_name', 'allow_linker', 'debug'] as $option) {
                $builder->setParameter('leapt_core.google_analytics.' . $option, $config['google_analytics'][$option]);
            }
        }
        if (isset($config['google_tags_manager'])) {
            foreach (['id'] as $option) {
                $builder->setParameter('leapt_core.google_tags_manager.' . $option, $config['google_tags_manager'][$option]);
            }
        }
    }

    private function configureHoneypot(ContainerBuilder $builder, array $config): void
    {
        if (isset($config['honeypot'])) {
            foreach (['enable_globally', 'input_name', 'css_class'] as $option) {
                $builder->setParameter('leapt_core.honeypot.' . $option, $config['honeypot'][$option]);
            }
        }
    }

    private function configurePaginator(ContainerBuilder $builder, array $config): void
    {
        // Handle paginator twig extension config
        if (isset($config['paginator'])) {
            foreach (['template'] as $option) {
                $builder->setParameter('leapt_core.paginator.' . $option, $config['paginator'][$option]);
            }
        }
    }

    private function configureRecaptcha(ContainerBuilder $builder, array $config): void
    {
        if (isset($config['recaptcha'])) {
            foreach ($config['recaptcha'] as $key => $value) {
                $builder->setParameter('leapt_core.recaptcha.' . $key, $value);
            }
        }
    }

    private function configureUploads(ContainerBuilder $builder, array $config): void
    {
        // Handle upload dir config
        $builder->setParameter('leapt_core.upload_dir', $config['upload_dir']);
    }
}
