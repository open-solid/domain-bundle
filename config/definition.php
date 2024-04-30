<?php

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\Messenger\MessageBusInterface;

return static function (DefinitionConfigurator $definition): void {
    $definition->rootNode()
        ->children()
            ->arrayNode('bus')
                ->addDefaultsIfNotSet()
                ->children()
                    ->enumNode('strategy')
                        ->defaultValue(interface_exists(MessageBusInterface::class) ? 'symfony' : 'native')
                        ->values(['symfony', 'native'])
                    ->end()
                ->end()
            ->end()
        ->end()
    ->end();
};
