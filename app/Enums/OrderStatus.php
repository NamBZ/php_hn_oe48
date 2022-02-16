<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class OrderStatus extends Enum
{
    const NEW_ORDER = 0;
    const IN_PROCCESS = 1;
    const IN_SHIPPING = 2;
    const COMPLETED = 3;
    const CANCELED = 4;
}
