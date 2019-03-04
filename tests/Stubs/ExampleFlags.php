<?php

declare(strict_types=1);

/**
 *
 * NOTICE OF LICENSE
 *
 * This source file is released under MIT license by Niko Granö.
 *
 * @copyright Niko Granö <niko9911@ironlions.fi> (https://granö.fi)
 *
 */

namespace Niko9911\Flags\Tests\Stubs;

use Niko9911\Flags\Flags;
use Niko9911\Flags\Bits;

final class ExampleFlags extends Flags
{
    public const FOO = Bits::BIT_1;
    public const BAR = Bits::BIT_2;
    public const BAZ = Bits::BIT_3;
    public const QUX = Bits::BIT_4;
}
