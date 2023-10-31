<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Encoders;

use Imagick;
use ImagickPixel;
use Intervention\Image\Drivers\Imagick\Encoders\BmpEncoder;
use Intervention\Image\Drivers\Imagick\Image;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;
use Intervention\MimeSniffer\MimeSniffer;
use Intervention\MimeSniffer\Types\ImageBmp;

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

        return new Image($imagick);
    }

    public function testEncode(): void
    {
        $image = $this->getTestImage();
        $encoder = new BmpEncoder();
        $result = $encoder->encode($image);
        $this->assertTrue(
            MimeSniffer::createFromString($result)->matches(new ImageBmp())
        );
    }

    public function testEncodeReduced(): void
    {
        $image = $this->createTestImage('gradient.bmp');
        $imagick = $image->frame()->core();
        $this->assertEquals(15, $imagick->getImageColors());
        $encoder = new BmpEncoder(2);
        $result = $encoder->encode($image);
        $imagick = new Imagick();
        $imagick->readImageBlob((string) $result);
        $this->assertEquals(2, $imagick->getImageColors());
    }
}
