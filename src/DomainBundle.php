<?php

declare(strict_types=1);

/*
 * This file is part of OpenSolid package.
 *
 * (c) Yonel Ceruto <open@yceruto.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSolid\DomainBundle;

use OpenSolid\DomainBundle\Attribute\AsDomainEventSubscriber;
use OpenSolid\Bus\Bridge\Symfony\DependencyInjection\CompilerPass\MessageHandlersLocatorPass;
use OpenSolid\Bus\Bridge\Symfony\DependencyInjection\Configurator\MessageHandlerConfigurator;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\Messenger\MessageBusInterface;

class DomainBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->import('../config/definition.php');
    }

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new MessageHandlersLocatorPass('domain.event.subscriber', 'domain.event.subscriber.middleware', [], true, 'event'));
    }

    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        if (interface_exists(MessageBusInterface::class)) {
            $container->import('../config/packages/messenger.php');
        }
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        if ('native' === $config['bus']['strategy']) {
            MessageHandlerConfigurator::configure($builder, AsDomainEventSubscriber::class, 'domain.event.subscriber');

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
