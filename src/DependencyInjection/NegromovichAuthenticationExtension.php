<?php
declare(strict_types=1);

namespace Negromovich\AuthenticationBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class NegromovichAuthenticationExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../config'));
        $loader->load('services.yaml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition('negromovich.authentication.firebase.factory');
        $definition->setArguments([$config['firebase_service_account_path']]);

        $this->addAuthConfig($config, $container);
    }

    private function addAuthConfig(array $config, ContainerBuilder $container): void
    {
        $definition = new Definition();
        $data = [
            'app' => $config['firebase_app_config'],
            'ui' => $config['firebase_ui_config'],
            'uiSelector' => $config['firebase_ui_selector'],
            'authRoute' => $config['auth_route'],
            'successRedirectRoute' => $config['success_redirect_route'],
        ];
        $definition->setArguments([$data]);
        $container->setDefinition(AuthConfig::class, $definition);
    }
}
