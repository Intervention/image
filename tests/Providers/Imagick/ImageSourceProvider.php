<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Providers\Imagick;

use Generator;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Tests\Providers\ImageSourceProvider as GenericImageSourceProvider;

class ImageSourceProvider extends GenericImageSourceProvider
{
    public static function filePaths(): Generator
    {
        foreach (parent::filePaths() as $filepath) {
            yield array_merge([new Driver()], $filepath);
        }
    }

    public static function binaryData(): Generator
    {
        foreach (parent::binaryData() as $binary) {
            yield array_merge([new Driver()], $binary);
        }
    }
}
