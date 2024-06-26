<?php


namespace PlusForta\PostmarkBundle\DependencyInjection;


use PlusForta\PostmarkBundle\PlusFortaPostmarkClientFactory;
use PlusForta\PostmarkBundle\Value\Email;
use PlusForta\PostmarkBundle\Value\EmailName;
use PlusForta\PostmarkBundle\Value\FromEmail;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class PlusFortaPostmarkExtension extends Extension
{

    /**
     * Loads a specific configuration.
     *
     * @throws \InvalidArgumentException|\Exception When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');


        $configuration = $this->getConfiguration($configs, $container);
        /** @psalm-suppress PossiblyNullArgument */
        $config = $this->processConfiguration($configuration, $configs);
        $definition = $container->getDefinition('PlusForta\PostmarkBundle\PlusFortaPostmarkClient');

        $definition->setArgument(2, $this->getServers($config['servers']));
        $definition->setArgument(3, $config['defaults']['from']['email']);
        $definition->setArgument(4, $config['defaults']['from']['name']);
        $definition->setArgument(5, $config['overrides']['to']['email']);
        $definition->setArgument(6, (bool) $config['disable_delivery']);
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return 'plusforta_postmark';
    }

    /**
     * @param array $serverConfig
     * @return array
     */
    private function getServers(array $serverConfig): array
    {
        $servers = [];
        foreach ($serverConfig as $server) {
            $servers[$server['name']] = $server['api_key'];
        }
        return $servers;
    }
}