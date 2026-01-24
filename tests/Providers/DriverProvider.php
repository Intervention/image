<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Providers;

use Generator;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;

class DriverProvider
{
    public static function drivers(): Generator
    {
        yield [new GdDriver()];
        yield [new ImagickDriver()];
    }

    public static function driverClassnames(): Generator
    {
        yield [GdDriver::class];
        yield [ImagickDriver::class];
    }
}
