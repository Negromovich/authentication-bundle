<?php
declare(strict_types=1);

namespace Negromovich\AuthenticationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('negromovich_authentication');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('firebase_service_account_path')->end()
                ->arrayNode('firebase_app_config')->isRequired()->variablePrototype()->end()->end()
                ->arrayNode('firebase_ui_config')->isRequired()->variablePrototype()->end()->end()
                ->scalarNode('firebase_ui_selector')->defaultValue('#firebaseui-auth-container')->end()
                ->scalarNode('auth_route')->defaultValue('negromovich_authentication_auth')->end()
                ->scalarNode('success_redirect_route')->defaultValue(null)->end()
            ->end();

        return $treeBuilder;
    }
}
