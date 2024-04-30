<?php

namespace OpenSolid\DomainEventBundle;

use OpenSolid\DomainEventBundle\Attribute\AsDomainEventSubscriber;
use OpenSolid\Messenger\Bridge\Symfony\DependencyInjection\CompilerPass\MessageHandlersLocatorPass;
use OpenSolid\Messenger\Bridge\Symfony\DependencyInjection\Configurator\MessageHandlerConfigurator;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\Messenger\MessageBusInterface;

class DomainEventBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition) : void
    {
        $definition->import('../config/definition.php');
    }

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new MessageHandlersLocatorPass('domain_event.subscriber', 'domain_event.middleware.subscriber', true));
    }

    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        if (interface_exists(MessageBusInterface::class)) {
            $container->import('../config/packages/messenger.php');
        }
    }
    
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder) : void
    {
        if ($config['bus']['strategy'] === 'native') {
            MessageHandlerConfigurator::configure($builder, AsDomainEventSubscriber::class, 'domain_event.subscriber');

            $container->import('../config/native.php');
        } else {
            if (!interface_exists(MessageBusInterface::class)) {
                throw new \LogicException('The "symfony" strategy requires symfony/messenger package.');
            }

            MessageHandlerConfigurator::configure($builder, AsDomainEventSubscriber::class, 'messenger.message_handler', ['bus' => 'event.bus']);

            $container->import('../config/messenger.php');
        }
    }
}
