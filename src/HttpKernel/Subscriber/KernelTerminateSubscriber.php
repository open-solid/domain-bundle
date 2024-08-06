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

namespace OpenSolid\DomainBundle\HttpKernel\Subscriber;

use OpenSolid\Bus\FlushableMessageBus;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final readonly class KernelTerminateSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private FlushableMessageBus $messageBus,
    ) {
    }

    public function __invoke(TerminateEvent $event): void
    {
        $this->messageBus->flush();
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::TERMINATE => '__invoke',
        ];
    }
}
