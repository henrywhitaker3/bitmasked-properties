<?php

namespace Henrywhitaker3\BitmaskedProperties\Tests\Utils;

use Henrywhitaker3\BitmaskedProperties\Interfaces\BitmaskEnum;

enum OptinEnum: int implements BitmaskEnum
{
    case EMAIL = 1 << 0;
    case SMS = 1 << 1;
}