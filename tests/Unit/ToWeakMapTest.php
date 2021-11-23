<?php

namespace Henrywhitaker3\BitmaskedProperties\Tests\Unit;

use Henrywhitaker3\BitmaskedProperties\Tests\TestCase;
use Henrywhitaker3\BitmaskedProperties\Tests\Utils\Person;
use Henrywhitaker3\BitmaskedProperties\Tests\Utils\OptinEnum;
use InvalidArgumentException;

class ToWeakMapTest extends TestCase
{
    private Person $person;

    public function setUp(): void
    {
        parent::setUp();

        $this->person = new Person;
    }

    public function test_all_are_false_when_none_are_set()
    {
        foreach ($this->person->getOptins() as $value) {
            $this->assertFalse($value);
        }
    }

    public function test_the_correct_entries_are_true()
    {
        $this->person->optin(OptinEnum::EMAIL);

        $this->assertTrue(
            $this->person->getOptins()[OptinEnum::EMAIL]
        );
        $this->assertFalse(
            $this->person->getOptins()[OptinEnum::SMS]
        );

        $this->person->optin(OptinEnum::SMS);

        $this->assertTrue(
            $this->person->getOptins()[OptinEnum::EMAIL]
        );
        $this->assertTrue(
            $this->person->getOptins()[OptinEnum::SMS]
        );

        $this->person->optout(OptinEnum::SMS);

        $this->assertTrue(
            $this->person->getOptins()[OptinEnum::EMAIL]
        );
        $this->assertFalse(
            $this->person->getOptins()[OptinEnum::SMS]
        );

        $this->person->optout(OptinEnum::EMAIL);

        $this->assertFalse(
            $this->person->getOptins()[OptinEnum::EMAIL]
        );
        $this->assertFalse(
            $this->person->getOptins()[OptinEnum::SMS]
        );
    }

    public function test_it_throws_an_exception_when_not_passed_a_bitwise_enum()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->person->flagToWeakmap('optin', RandomEnum::class);
    }

    public function test_it_throws_an_exception_when_passed_a_random_string()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->person->flagToWeakmap('optin', 'bongo');
    }
}
