# Domain Bundle for Symfony framework

A Symfony bundle for domain building blocks (https://github.com/open-solid/domain).

## Installation

```console
$ composer require open-solid/domain-bundle
```

## Usage

```php
use OpenSolid\Domain\Event\Bus\EventBus;
use OpenSolid\Domain\Event\DomainEvent;
use OpenSolid\DomainBundle\Attribute\AsDomainEventSubscriber;

class UserRegistered extends DomainEvent
{
}

#[AsDomainEventSubscriber]
class UserRegisteredHandler
{
    public function __invoke(UserRegistered $event): void
    {
        // Handle the event
    }
}

class UserService
{
    public function __construct(private EventBus $eventBus)
    {
    }

    public function registerUser(): void
    {
        // Register the user

        $this->eventBus->publish(new UserRegistered('uuid'));
    }
}
```

## License

This software is published under the [MIT License](LICENSE)
