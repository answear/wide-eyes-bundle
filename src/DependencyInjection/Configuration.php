<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    private const SIMILAR_API_URL = 'https://pro.api-mirror.wide-eyes.it';
    private const SEARCH_BY_IMAGE_API_URL = 'https://api.wide-eyes.it';
    private const CONNECTION_TIMEOUT = 10;
    private const SIMILAR_REQUEST_TIMEOUT = 1;
    private const SEARCH_BY_IMAGE_REQUEST_TIMEOUT = 5;

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('answear_wide_eyes');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('publicKey')->cannotBeEmpty()->end()
                ->floatNode('connectionTimeout')->defaultValue(self::CONNECTION_TIMEOUT)->end()
                ->arrayNode('similar')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('apiUrl')->defaultValue(self::SIMILAR_API_URL)->end()
                        ->floatNode('requestTimeout')->defaultValue(self::SIMILAR_REQUEST_TIMEOUT)->end()
                    ->end()
                ->end()
                ->arrayNode('searchByImage')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('apiUrl')->defaultValue(self::SEARCH_BY_IMAGE_API_URL)->end()
                        ->floatNode('requestTimeout')->defaultValue(self::SEARCH_BY_IMAGE_REQUEST_TIMEOUT)->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
