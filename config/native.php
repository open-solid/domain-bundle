<?php

use OpenSolid\DomainEvent\Bus\DomainEventBus;
use OpenSolid\DomainEvent\Bus\NativeDomainEventBus;
use OpenSolid\DomainEventBundle\HttpKernel\Subscriber\KernelTerminateSubscriber;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use OpenSolid\Messenger\Bus\NativeLazyMessageBus;
use OpenSolid\Messenger\Bus\NativeMessageBus;
use OpenSolid\Messenger\Handler\HandlersCountPolicy;
use OpenSolid\Messenger\Middleware\HandleMessageMiddleware;
use OpenSolid\Messenger\Middleware\LogMessageMiddleware;
use function Symfony\Component\DependencyInjection\Loader\Configurator\abstract_arg;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('domain_event.middleware.logger', LogMessageMiddleware::class)
            ->args([
                service('logger'),
                'domain event',
            ])
            ->tag('domain_event.middleware')

        ->set('domain_event.middleware.subscriber', HandleMessageMiddleware::class)
            ->args([
                abstract_arg('domain_event.subscriber.locator'),
                HandlersCountPolicy::NO_HANDLER,
                service('logger'),
                'Domain event',
            ])
            ->tag('domain_event.middleware')

        ->set('domain_event.bus.native', NativeMessageBus::class)
            ->args([
                tagged_iterator('domain_event.middleware'),
            ])

        ->set('domain_event.bus.native.lazy', NativeLazyMessageBus::class)
            ->args([
                service('domain_event.bus.native'),
            ])

        ->set('domain_event.bus', NativeDomainEventBus::class)
            ->args([
                service('domain_event.bus.native.lazy'),
            ])

        ->alias(DomainEventBus::class, 'domain_event.bus')

        ->set('domain_event.kernel.subscriber.terminate', KernelTerminateSubscriber::class)
            ->args([
                service('domain_event.bus'),
            ])
            ->tag('kernel.event_subscriber')
    ;
};
