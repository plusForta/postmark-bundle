<?php


namespace PlusForta\PostmarkBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('plusforta_postmark');
        $treeBuilder->getRootNode()->children()
            ->arrayNode('servers')
                ->arrayPrototype()
                    ->scalarPrototype()->end()
                    ->scalarPrototype()->end()
                ->end()
            ->end()
            ->arrayNode('defaults')->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('from')
                        ->children()
                            ->scalarNode('email')->defaultValue('info@plusforta.de')->end()
                            ->scalarNode('name')->defaultNull()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->arrayNode('overrides')->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('to')
                        ->children()
                            ->scalarNode('email')->defaultNull()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->booleanNode('disable_delivery')->defaultFalse()->end()
        ->end();

        return $treeBuilder;
    }
}