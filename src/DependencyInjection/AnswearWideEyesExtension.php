<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\DependencyInjection;

use Answear\WideEyesBundle\Service\ConfigProvider;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class AnswearWideEyesExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yaml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition(ConfigProvider::class);
        $definition->setArguments(
            [
                $config['similarApiUrl'],
                $config['searchByImageApiUrl'],
                $config['publicKey'],
                $config['requestTimeout'],
                $config['connectionTimeout'],
            ]
        );
    }
}
