<?php

use OpenSolid\Domain\Event\DomainEvent;
use Symfony\Component\DependencyInjection\ContainerBuilder;

return static function (ContainerBuilder $container) {
    $container->prependExtensionConfig('framework', [
        'messenger' => [
            'buses' => [
                'event.bus' => [
                    'default_middleware' => 'allow_no_handlers',
                    'middleware' => [
                        'router_context',
                    ],
                ],
            ],
            'transports' => [
                'async' => [
                    'dsn' => '%env(MESSENGER_TRANSPORT_DSN)%',
                ],
            ],
            'routing' => [
                DomainEvent::class => 'async',
            ],
        ],
    ]);
};
