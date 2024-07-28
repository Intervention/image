<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Traits;

use Intervention\Image\Traits\CanBuildFilePointer;

trait CanInspectPng
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

    /**
     * Try to detect PNG color type from given binary data
     *
     * @param string $data
     * @return string
     */
    private function pngColorType(string $data): string
    {
        if (substr($data, 1, 3) !== 'PNG') {
            return 'unkown';
        }

        $pos = strpos($data, 'IHDR');
        $type = substr($data, $pos + 13, 1);

        return match (unpack('C', $type)[1]) {
            0 => 'grayscale',
            2 => 'truecolor',
            3 => 'indexed',
            4 => 'grayscale-alpha',
            6 => 'truecolor-alpha',
            default => 'unknown',
        };
    }
}
