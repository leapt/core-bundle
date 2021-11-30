<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\DependencyInjection;

use Leapt\CoreBundle\Datalist\Action\Type\ActionTypeInterface;
use Leapt\CoreBundle\Datalist\Field\Type\FieldTypeInterface;
use Leapt\CoreBundle\Datalist\Filter\Type\FilterTypeInterface;
use Leapt\CoreBundle\Datalist\Type\DatalistTypeInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class LeaptCoreExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        // Handle upload dir config
        $container->setParameter('leapt_core.upload_dir', $config['upload_dir']);

        // Handle analytics config
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
        // Handle facebook config
        if (isset($config['facebook'])) {
            foreach (['app_id'] as $option) {
                $container->setParameter('leapt_core.facebook.' . $option, $config['facebook'][$option]);
            }
        }

        // Handle paginator twig extension config
        if (isset($config['paginator'])) {
            foreach (['template'] as $option) {
                $container->setParameter('leapt_core.paginator.' . $option, $config['paginator'][$option]);
            }
        }

        // Handle recaptcha twig extension config
        if (isset($config['recaptcha'])) {
            foreach ($config['recaptcha'] as $key => $value) {
                $container->setParameter('leapt_core.recaptcha.' . $key, $value);
            }
        }

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
}
