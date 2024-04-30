<?php

namespace OpenSolid\DomainEventBundle\HttpKernel\Subscriber;

use OpenSolid\Messenger\Bus\FlushableMessageBus;
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
