<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Encoders;

use Imagick;
use ImagickPixel;
use Intervention\Image\Collection;
use Intervention\Image\Drivers\Imagick\Encoders\JpegEncoder;
use Intervention\Image\Drivers\Imagick\Frame;
use Intervention\Image\Drivers\Imagick\Image;
use Intervention\Image\Tests\TestCase;
use Intervention\MimeSniffer\MimeSniffer;
use Intervention\MimeSniffer\Types\ImageJpeg;

/**
 * @covers \Intervention\Image\Drivers\Imagick\Encoders\JpegEncoder
 */
class JpegEncoderTest extends TestCase
{
    protected function getTestImage(): Image
    {
        $imagick = new Imagick();
        $imagick->newImage(3, 2, new ImagickPixel('red'), 'png');
        $frame = new Frame($imagick);

        return new Image(new Collection([$frame]));
    }

    public function testEncode(): void
    {
        $image = $this->getTestImage();
        $encoder = new JpegEncoder(75);
        $result = $encoder->encode($image);
        $this->assertTrue(MimeSniffer::createFromString($result)->matches(new ImageJpeg()));
    }
}
