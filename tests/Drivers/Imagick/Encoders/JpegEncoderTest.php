<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Encoders;

use Imagick;
use ImagickPixel;
use Intervention\Image\Drivers\Imagick\Encoders\JpegEncoder;
use Intervention\Image\Drivers\Imagick\Image;
use Intervention\Image\Tests\TestCase;
use Intervention\MimeSniffer\MimeSniffer;
use Intervention\MimeSniffer\Types\ImageJpeg;

/**
 * @requires extension imagick
 * @covers \Intervention\Image\Drivers\Imagick\Encoders\JpegEncoder
 */
class JpegEncoderTest extends TestCase
{
    protected function getTestImage(): Image
    {
        $imagick = new Imagick();
        $imagick->newImage(3, 2, new ImagickPixel('red'), 'png');

        return new Image($imagick);
    }

    public function testEncode(): void
    {
        $image = $this->getTestImage();
        $encoder = new JpegEncoder(75);
        $result = $encoder->encode($image);
        $this->assertTrue(MimeSniffer::createFromString($result)->matches(new ImageJpeg()));
    }
}
