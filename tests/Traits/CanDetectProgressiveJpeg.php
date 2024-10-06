<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Traits;

use Intervention\Image\EncodedImage;
use Intervention\Image\Traits\CanBuildFilePointer;

trait CanDetectProgressiveJpeg
{
    use CanBuildFilePointer;

    /**
     * Checks if the given image data is progressive encoded Jpeg format
     *
     * @param EncodedImage $imagedata
     * @return bool
     */
    private function isProgressiveJpeg(EncodedImage $image): bool
    {
        $f = $image->toFilePointer();

        while (!feof($f)) {
            if (unpack('C', fread($f, 1))[1] !== 0xff) {
                return false;
            }

            $blockType = unpack('C', fread($f, 1))[1];

            switch (true) {
                case $blockType == 0xd8:
                case $blockType >= 0xd0 && $blockType <= 0xd7:
                    break;

                case $blockType == 0xc0:
                    fclose($f);
                    return false;

                case $blockType == 0xc2:
                    fclose($f);
                    return true;

                case $blockType == 0xd9:
                    break 2;

                default:
                    $blockSize = unpack('n', fread($f, 2))[1];
                    fseek($f, $blockSize - 2, SEEK_CUR);
                    break;
            }
        }

        fclose($f);

        return false;
    }
}
