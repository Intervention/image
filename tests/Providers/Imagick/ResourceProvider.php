<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Providers\Imagick;

use Generator;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Tests\Providers\ResourceProvider as GenericResourceProvider;
use Intervention\Image\Format;

class ResourceProvider extends GenericResourceProvider
{
    public static function baseData(): Generator
    {
        $driver = new Driver();

        foreach (parent::baseData() as $yield) {
            if ($driver->supports($yield['format'])) {
                yield ['driver' => new Driver(), ...$yield];
            }
        }
    }

    public static function sizeData(): Generator
    {
        foreach (static::baseData() as $yield) {
            yield [$yield['driver'], $yield['resource'], $yield['size']];
        }
    }

    public static function colorspaceData(): Generator
    {
        foreach (static::baseData() as $yield) {
            yield [$yield['driver'], $yield['resource'], $yield['colorspace']];
        }
    }

    public static function resolutionData(): Generator
    {
        foreach (static::baseData() as $yield) {
            if (in_array($yield['format'], [Format::JPEG, Format::PNG])) {
                yield [$yield['driver'], $yield['resource'], $yield['resolution']];
            }
        }
    }
}
