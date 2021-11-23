<?php

namespace Henrywhitaker3\BitmaskedProperties\Tests\Utils;

use Henrywhitaker3\BitmaskedProperties\Traits\HasBitmaskedProperties;
use WeakMap;

class Person
{
    use HasBitmaskedProperties;

    public function __construct(public int $optin = 0)
    {
        //
    }

    public function optin(OptinEnum $type): void
    {
        $this->setFlag('optin', $type, true);
    }

    public function optout(OptinEnum $type): void
    {
        $this->setFlag('optin', $type, false);
    }

    public function isOptedIn(OptinEnum $type): bool
    {
        return $this->getFlag('optin', $type);
    }

    public function getOptins(): WeakMap
    {
        return $this->flagToWeakmap('optin', OptinEnum::class);
    }
}
