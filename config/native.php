<?php

use OpenSolid\Bus\Handler\MessageHandlersCountPolicy;
use OpenSolid\Bus\Middleware\HandlingMiddleware;
use OpenSolid\Bus\Middleware\LoggingMiddleware;
use OpenSolid\Bus\NativeLazyMessageBus;
use OpenSolid\Bus\NativeMessageBus;
use OpenSolid\Domain\Event\Bus\EventBus;
use OpenSolid\Domain\Event\Bus\NativeEventBus;
use OpenSolid\DomainBundle\HttpKernel\Subscriber\KernelTerminateSubscriber;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\abstract_arg;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('domain.event.logger.middleware', LoggingMiddleware::class)
            ->args([
                service('logger'),
                'domain event',
            ])
            ->tag('domain.event.middleware')

        ->set('domain.event.subscriber.middleware', HandlingMiddleware::class)
            ->args([
                abstract_arg('domain.event.subscriber.locator'),
                MessageHandlersCountPolicy::NO_HANDLER,
                service('logger'),
                'Domain event',
            ])
            ->tag('domain.event.middleware')

        ->set('domain.event.bus.native', NativeMessageBus::class)
            ->args([
                tagged_iterator('domain.event.middleware'),
            ])

        ->set('domain.event.bus.native.lazy', NativeLazyMessageBus::class)
            ->args([
                service('domain.event.bus.native'),
            ])

        ->set('domain.event.bus', NativeEventBus::class)
            ->args([
                service('domain.event.bus.native.lazy'),
            ])

        ->alias(EventBus::class, 'domain.event.bus')

        ->set('domain.event.kernel.subscriber.terminate', KernelTerminateSubscriber::class)
            ->args([
                service('domain.event.bus'),
            ])
            ->tag('kernel.event_subscriber')
    ;
};
