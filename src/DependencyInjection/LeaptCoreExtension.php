<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\DependencyInjection;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldConfiguratorInterface;
use Leapt\CoreBundle\Datalist\Action\Type\ActionTypeInterface;
use Leapt\CoreBundle\Datalist\Field\Type\FieldTypeInterface;
use Leapt\CoreBundle\Datalist\Filter\Type\FilterTypeInterface;
use Leapt\CoreBundle\Datalist\Type\DatalistTypeInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class LeaptCoreExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\PhpFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.php');

        $this->configureDatalist($container);
        $this->configureEasyAdmin($loader);
        $this->configureFacebook($container, $config);
        $this->configureGoogle($container, $config);
        $this->configureHoneypot($container, $config);
        $this->configurePaginator($container, $config);
        $this->configureRecaptcha($container, $config);
        $this->configureUploads($container, $config);
    }

    private function configureDatalist(ContainerBuilder $container): void
    {
        // Auto-register Datalist types
        $container->registerForAutoconfiguration(ActionTypeInterface::class)
            ->addTag('leapt_core.datalist.action_type');
        $container->registerForAutoconfiguration(FieldTypeInterface::class)
            ->addTag('leapt_core.datalist.field_type');
        $container->registerForAutoconfiguration(FilterTypeInterface::class)
            ->addTag('leapt_core.datalist.filter_type');
        $container->registerForAutoconfiguration(DatalistTypeInterface::class)
            ->addTag('leapt_core.datalist.type');
    }

    private function configureEasyAdmin(Loader\PhpFileLoader $loader): void
    {
        if (interface_exists(FieldConfiguratorInterface::class)) {
            $loader->load('services_easyadmin.php');
        }
    }

    private function configureFacebook(ContainerBuilder $container, array $config): void
    {
        if (isset($config['facebook'])) {
            foreach (['app_id'] as $option) {
                $container->setParameter('leapt_core.facebook.' . $option, $config['facebook'][$option]);
            }
        }
    }

    private function configureGoogle(ContainerBuilder $container, array $config): void
    {
        if (isset($config['google_analytics'])) {
            foreach (['tracking_id', 'domain_name', 'allow_linker', 'debug'] as $option) {
                $container->setParameter('leapt_core.google_analytics.' . $option, $config['google_analytics'][$option]);
            }
        }
        if (isset($config['google_tags_manager'])) {
            foreach (['id'] as $option) {
                $container->setParameter('leapt_core.google_tags_manager.' . $option, $config['google_tags_manager'][$option]);
            }
        }
    }

    private function configureHoneypot(ContainerBuilder $container, array $config): void
    {
        if (isset($config['honeypot'])) {
            foreach (['enable_globally', 'input_name', 'css_class'] as $option) {
                $container->setParameter('leapt_core.honeypot.' . $option, $config['honeypot'][$option]);
            }
        }
    }

    private function configurePaginator(ContainerBuilder $container, array $config): void
    {
        // Handle paginator twig extension config
        if (isset($config['paginator'])) {
            foreach (['template'] as $option) {
                $container->setParameter('leapt_core.paginator.' . $option, $config['paginator'][$option]);
            }
        }
    }

    private function configureRecaptcha(ContainerBuilder $container, array $config): void
    {
        if (isset($config['recaptcha'])) {
            foreach ($config['recaptcha'] as $key => $value) {
                $container->setParameter('leapt_core.recaptcha.' . $key, $value);
            }
        }
    }

    private function configureUploads(ContainerBuilder $container, array $config): void
    {
        // Handle upload dir config
        $container->setParameter('leapt_core.upload_dir', $config['upload_dir']);
    }
}
