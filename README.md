# DomainEventBundle

A Symfony bundle for handling domain events (https://github.com/open-solid/domain-event).

## Installation

```console
$ composer require open-solid/domain-event-bundle
```

## Usage

```php
use OpenSolid\DomainEvent\DomainEvent;
use OpenSolid\DomainEventBundle\Attribute\AsDomainEventSubscriber;

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
    public function __construct(private DomainEventPublisher $publisher)
    {
    }

    public function registerUser(): void
    {
        // Register the user

        $this->publisher->publish(new UserRegistered('uuid'));
    }
}
```

## License

This software is published under the [MIT License](LICENSE)
