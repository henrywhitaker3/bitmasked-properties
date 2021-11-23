<?php

namespace Henrywhitaker3\BitmaskedProperties\Tests\Unit;

use Henrywhitaker3\BitmaskedProperties\Tests\TestCase;
use Henrywhitaker3\BitmaskedProperties\Tests\Utils\Person;
use Henrywhitaker3\BitmaskedProperties\Tests\Utils\OptinEnum;

class BitmaskedFlagsTest extends TestCase
{
    private Person $person;

    public function setUp(): void
    {
        parent::setUp();

        $this->person = new Person;
    }

    public function test_it_sets_the_raw_values_of_the_flag_correctly()
    {
        $this->assertEquals($current = 0, $this->person->optin);

        $this->person->optin(OptinEnum::SMS);
        $this->assertEquals($current = $current + OptinEnum::SMS->value, $this->person->optin);

        $this->person->optin(OptinEnum::EMAIL);
        $this->assertEquals(
            $current = $current + OptinEnum::EMAIL->value,
            $this->person->optin
        );

        $this->person->optout(OptinEnum::EMAIL);
        $this->assertEquals(
            $current = $current - OptinEnum::EMAIL->value,
            $this->person->optin
        );

        $this->person->optout(OptinEnum::SMS);
        $this->assertEquals(
            $current = $current - OptinEnum::SMS->value,
            $this->person->optin
        );
    }

    public function test_it_determines_if_the_flag_is_set_correctly()
    {
        $this->assertFalse($this->person->isOptedIn(OptinEnum::SMS));
        $this->assertFalse($this->person->isOptedIn(OptinEnum::EMAIL));

        $this->person->optin(OptinEnum::SMS);

        $this->assertTrue($this->person->isOptedIn(OptinEnum::SMS));
        $this->assertFalse($this->person->isOptedIn(OptinEnum::EMAIL));

        $this->person->optin(OptinEnum::EMAIL);

        $this->assertTrue($this->person->isOptedIn(OptinEnum::SMS));
        $this->assertTrue($this->person->isOptedIn(OptinEnum::EMAIL));

        $this->person->optout(OptinEnum::EMAIL);

        $this->assertTrue($this->person->isOptedIn(OptinEnum::SMS));
        $this->assertFalse($this->person->isOptedIn(OptinEnum::EMAIL));

        $this->person->optout(OptinEnum::SMS);

        $this->assertFalse($this->person->isOptedIn(OptinEnum::SMS));
        $this->assertFalse($this->person->isOptedIn(OptinEnum::EMAIL));
    }
}
