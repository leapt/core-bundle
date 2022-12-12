<?php

declare(strict_types=1);

namespace Leapt\CoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('leapt_core');
        $rootNode = $treeBuilder->getRootNode();
        \assert($rootNode instanceof ArrayNodeDefinition);

        $this->addFacebookSection($rootNode);
        $this->addGoogleSection($rootNode);
        $this->addPaginatorSection($rootNode);
        $this->addRecaptchaSection($rootNode);
        $this->addUploadsSection($rootNode);

        return $treeBuilder;
    }

    private function addFacebookSection(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
                ->arrayNode('facebook')
                ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('app_id')->defaultNull()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addGoogleSection(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
                ->arrayNode('google_analytics')
                ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('tracking_id')->defaultNull()->end()
                        ->scalarNode('domain_name')->defaultValue('auto')->end()
                        ->booleanNode('allow_linker')->defaultValue(false)->end()
                        ->booleanNode('debug')->defaultValue(false)->end()
                    ->end()
                ->end()
                ->arrayNode('google_tags_manager')
                ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('id')->defaultNull()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addPaginatorSection(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
                ->arrayNode('paginator')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('template')->defaultValue('@LeaptCore/Paginator/paginator_default_layout.html.twig')->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addRecaptchaSection(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
                ->arrayNode('recaptcha')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('public_key')->defaultNull()->end()
                        ->scalarNode('private_key')->defaultNull()->end()
                        ->booleanNode('enabled')->defaultTrue()->end()
                        ->booleanNode('verify_host')->defaultFalse()->end()
                        ->booleanNode('ajax')->defaultFalse()->end()
                        ->scalarNode('locale_key')->defaultValue('%kernel.default_locale%')->end()
                        ->booleanNode('locale_from_request')->defaultFalse()->end()
                        ->scalarNode('api_host')->defaultValue('www.google.com')->end()
                        ->booleanNode('hide_badge')->defaultValue(false)->end()
                        ->floatNode('score_threshold')->min(0.0)->max(1.0)->defaultValue(0.5)->end()
                        ->arrayNode('http_proxy')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('host')->defaultValue(null)->end()
                                ->scalarNode('port')->defaultValue(null)->end()
                                ->scalarNode('auth')->defaultValue(null)->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addUploadsSection(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
                ->scalarNode('upload_dir')->defaultValue('%kernel.project_dir%/public')->end()
            ->end()
        ;
    }
}
