<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Traits;

use Intervention\Image\Traits\CanBuildFilePointer;

trait CanInspectPngFormat
{
    use CanBuildFilePointer;

    /**
     * Checks if the given image data is interlaced encoded PNG format
     *
     * @param string $imagedata
     * @return bool
     */
    private function isInterlacedPng(string $imagedata): bool
    {
        $f = $this->buildFilePointer($imagedata);
        $contents = fread($f, 32);
        fclose($f);

        return ord($contents[28]) != 0;
    }
}
