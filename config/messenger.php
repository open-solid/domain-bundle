<?php

use OpenSolid\Domain\Event\Bus\EventBus;
use OpenSolid\Domain\Event\Bus\Bridge\SymfonyEventBus;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('domain.event.bus', SymfonyEventBus::class)
            ->args([
                service('event.bus'),
            ])

        ->alias(EventBus::class, 'domain.event.bus')
    ;
};
