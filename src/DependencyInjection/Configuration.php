<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    private const API_URL = 'https://api.wide-eyes.it';
    private const CONNECTION_TIMEOUT = 10;
    private const REQUEST_TIMEOUT = 1;

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('answear_wide_eyes');

        $treeBuilder->getRootNode()
            ->children()
            ->scalarNode('apiUrl')->defaultValue(self::API_URL)->end()
            ->scalarNode('publicKey')->cannotBeEmpty()->end()
            ->floatNode('connectionTimeout')->defaultValue(self::CONNECTION_TIMEOUT)->end()
            ->floatNode('requestTimeout')->defaultValue(self::REQUEST_TIMEOUT)->end()
            ->end();

        return $treeBuilder;
    }
}
