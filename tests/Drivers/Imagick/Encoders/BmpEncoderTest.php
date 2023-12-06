<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Encoders;

use Imagick;
use ImagickPixel;
use Intervention\Image\Drivers\Imagick\Core;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Encoders\BmpEncoder;
use Intervention\Image\Image;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

/**
 * @requires extension imagick
 * @covers \Intervention\Image\Drivers\Imagick\Encoders\JpegEncoder
 */
class BmpEncoderTest extends TestCase
{
    use CanCreateImagickTestImage;

    protected function getTestImage(): Image
    {
        $imagick = new Imagick();
        $imagick->newImage(3, 2, new ImagickPixel('red'), 'png');

        return new Image(
            new Driver(),
            new Core($imagick)
        );
    }

    public function testEncode(): void
    {
        $image = $this->getTestImage();
        $encoder = new BmpEncoder();
        $result = $encoder->encode($image);
        $this->assertMimeType(['image/bmp', 'image/x-ms-bmp'], (string) $result);
    }

    public function testEncodeReduced(): void
    {
        $image = $this->readTestImage('gradient.bmp');
        $imagick = $image->core()->native();
        $this->assertEquals(15, $imagick->getImageColors());
        $encoder = new BmpEncoder(2);
        $result = $encoder->encode($image);
        $imagick = new Imagick();
        $imagick->readImageBlob((string) $result);
        $this->assertEquals(2, $imagick->getImageColors());
    }
}
