# Bitmasked Properties

This library provides a trait to use bitmasked properties with PHP 8.1 enums. I was reading [this](https://aaronfrancis.com/2021/bitmasking-in-laravel-and-mysql) and could think a few different places where this would be useful for me, so I decided to make this library to make it a bit more re-usable; it also felt like a good way to start playing around with PHP 8.1 enums.

## Installation

```shell
composer require henrywhitaker3/bitmasked-properties
```

## Usage

For example, you have a model `Person` that can be opted in or out for email and sms comms. A simple (not v good) way of doing this could be having individual columns in the database to store each of these methods:

```php
class Person
{
    public function __construct(
        public bool $sms,
        public bool $email
    ) {}

    public function isOptedIn(string $type): bool
    {
        // Should really check the value of type is valid first...
        return $this->{$type};
    }

    public function optin(string $type): void
    {
        $this->updateOptin($type, true);
    }

    public function optout(string $type): void
    {
        $this->updateOptin($type, false);
    }

    private function updateOptin(string $type, bool $value): void
    {
        switch($type) {
            case 'sms':
                $this->sms = $value;
                break;
            case 'email':
                $this->email = $value;
                break;
        }
    }
}
```

This requires hard-coding the field names and having to run migrations to add columns when a new communication type comes along, which is a bit gross.

For some scenarios, using a bitmasked field would be a far nicer solution - only some as you can't index these values and therefore can't query them very efficiently. But, it allows you to just add a new case to the enum whenever a new communication type gets added with no change to the database. Using this you can have up to 32 different boolean values in a standard integer field. Here's how to use it for the person example above:

```php
enum Optin: int implements BitmaskEnum
{
    case SMS = 1 << 0; // 1
    case EMAIL = 1 << 1; // 2
}
```

```php
class Person
{
    use HasBitmaskedProperties;

    public function __construct(
        public bool $optin
    ) {}

    public function isOptedIn(Optin $type): bool
    {
        return $this->getFlag('optin', $type);
    }

    public function optin(Optin $type): void
    {
        $this->setFlag('optin', $type, true);
    }

    public function optout(Optin $type): void
    {
        $this->setFlag('optin', $type, false);
    }

    public function getOptins(): WeakMap
    {
        return $this->flagToWeakmap('optin', Optin::class);
    }
}
```

Now it's really simple to use:

```php
$person = new Person; // $optin === 0

$person->isOptedIn(Optin::SMS); // returns false
$person->optin(Optin::SMS); // $optin === 1
$person->isOptedIn(Optin::SMS); // returns true

$person->optin(Optin::EMAIL); // $optin === 3
$person->isOptedIn(Optin::EMAIL); // returns true

$person->optout(Optin::SMS); // $optin === 2
$person->isOptedIn(Optin::SMS); // returns false

$person->getOptins()[Optin::EMAIL]; // return true
```

You can add a new value to the `Optin` enum with no changes to the database or code.
