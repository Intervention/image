<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Traits;

use Intervention\Image\EncodedImage;
use Intervention\Image\Traits\CanBuildFilePointer;

trait CanDetectInterlacedPng
{
    use CanBuildFilePointer;

    /**
     * Checks if the given image data is interlaced encoded PNG format
     *
     * @param EncodedImage $image
     * @return bool
     */
    private function isInterlacedPng(EncodedImage $image): bool
    {
        $contents = fread($image->toFilePointer(), 32);

        return ord($contents[28]) != 0;
    }
}
