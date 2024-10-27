<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use Intervention\Image\Tests\BaseTestCase;
use Imagick;
use ImagickPixel;

final class HeicTest extends BaseTestCase
{
    public function testEncoding(): void
    {
        // create
        $background = new ImagickPixel('rgba(255, 255, 255, 0)');
        $imagick = new Imagick();
        $imagick->newImage(100, 100, $background, 'png');
        $imagick->setType(Imagick::IMGTYPE_UNDEFINED);
        $imagick->setImageType(Imagick::IMGTYPE_UNDEFINED);
        $imagick->setColorspace(Imagick::COLORSPACE_SRGB);
        $imagick->setImageResolution(96, 96);
        $imagick->setImageBackgroundColor($background);

        // encode
        $imagick->setFormat('HEIC');
        $imagick->setImageFormat('HEIC');
        $imagick->setCompressionQuality(75);
        $imagick->setImageCompressionQuality(75);
        $encoded = $imagick->getImagesBlob();

        // re-read
        $imagick = new Imagick();
        $imagick->readImageBlob($encoded);

        $this->assertInstanceOf(Imagick::class, $imagick);
    }
}
