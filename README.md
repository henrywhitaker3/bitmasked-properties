# Bitwise example

This is an example of how we could use bitwise operations to reduce db columns. 

A standard integer column is 32-bits, using this we could store 32 boolean values in that 1 column. This is similar to unix permissions:

- Read: 4
- Write: 2
- Execute: 1

Where read and write is 4 for example.

The example is in `src/go.php`:

```php
$person = new Person;
printOptin($person);
// Raw value: 1
// Person is opted in for DEFAULT

$person->optin(MessageType::OTP);
printOptin($person);
// Raw value: 5
// Person is opted in for DEFAULT
// Person is opted in for OTP

$person->optin(MessageType::MARKETING);
printOptin($person);
// Raw value: 7
// Person is opted in for DEFAULT
// Person is opted in for MARKETING
// Person is opted in for OTP

$person->optout(MessageType::OTP);
printOptin($person);
// Raw value: 3
// Person is opted in for DEFAULT
// Person is opted in for MARKETING
```
