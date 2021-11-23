<?php

namespace Henrywhitaker3\BitmaskedProperties\Traits;

use Henrywhitaker3\BitmaskedProperties\Interfaces\BitmaskEnum;
use InvalidArgumentException;
use ReflectionClass;
use WeakMap;

trait HasBitmaskedProperties
{
    /**
     * Get the value of a flag.
     *
     * @param string $property
     * @param BitmaskEnum $value
     * @return bool
     */
    public function getFlag(string $property, BitmaskEnum $value): bool
    {
        return ($this->{$property} & $value->value) === $value->value;
    }

    /**
     * Set the value of a flag.
     *
     * @param string $property
     * @param BitmaskEnum $value
     * @param bool $enabled
     * @return void
     */
    public function setFlag(string $property, BitmaskEnum $value, bool $enabled = true): void
    {
        if ($enabled) {
            $this->enableFlag($property, $value);
        } else {
            $this->disableFlag($property, $value);
        }
    }

    /**
     * Set the value for a given flag to true.
     *
     * @param string $property
     * @param BitmaskEnum $value
     * @return void
     */
    private function enableFlag(string $property, BitmaskEnum $value): void
    {
        $this->{$property} |=  $value->value;
    }

    /**
     * Set the value for a given case to false.
     *
     * @param string $property
     * @param BitmaskEnum $value
     * @return void
     */
    private function disableFlag(string $property, BitmaskEnum $value): void
    {
        $this->{$property} &= ~$value->value;
    }

    /**
     * Either pass the FQCN of a BitmaskEnum enum.
     *
     * @param string $cases
     * @return WeakMap
     */
    public function flagToWeakmap(string $property, string $enum): WeakMap
    {
        if (
            !class_exists($enum) ||
            !(new ReflectionClass($enum))->implementsInterface(BitmaskEnum::class)
        ) {
            throw new InvalidArgumentException(
                'The string passed to this method must be a valid BitmaskEnum.'
            );
        }

        $weakmap = new WeakMap;

        foreach ($enum::cases() as $type) {
            $weakmap[$type] = $this->getFlag($property, $type);
        }

        return $weakmap;
    }
}
