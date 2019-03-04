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

final class ExampleFlagsWithNames extends Flags
{
    public const FOO = Bits::BIT_1;
    public const BAR = Bits::BIT_2;
    public const BAZ = Bits::BIT_3;
    public const QUX = Bits::BIT_4;

    public static function registerFlags(): array
    {
        return [
            static::FOO => 'My foo description',
            static::BAR => 'My bar description',
            static::BAZ => 'My baz description',
            static::QUX => 'My qux description',
        ];
    }
}
