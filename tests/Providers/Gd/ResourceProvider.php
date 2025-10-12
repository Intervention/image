<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Providers\Gd;

use Generator;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Tests\Providers\ResourceProvider as GenericResourceProvider;

class ResourceProvider extends GenericResourceProvider
{
    public static function baseData(): Generator
    {
        foreach (parent::baseData() as $yield) {
            yield ['driver' => new Driver(), ...$yield];
        }
    }

    public static function sizeData(): Generator
    {
        foreach (static::baseData() as $yield) {
            yield [$yield['driver'], $yield['resource'], $yield['size']];
        }
    }
}
