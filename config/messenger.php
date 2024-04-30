<?php

use OpenSolid\DomainEvent\Bus\DomainEventBus;
use OpenSolid\DomainEvent\Bus\SymfonyDomainEventBus;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('domain_event.bus', SymfonyDomainEventBus::class)
            ->args([
                service('event.bus'),
            ])

        ->alias(DomainEventBus::class, 'domain_event.bus')
    ;
};
