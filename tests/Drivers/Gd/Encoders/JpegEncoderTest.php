<?php

namespace Intervention\Image\Tests\Drivers\Gd\Encoders;

use Intervention\Image\Collection;
use Intervention\Image\Drivers\Gd\Encoders\JpegEncoder;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Drivers\Gd\Image;
use Intervention\Image\Tests\TestCase;
use Intervention\MimeSniffer\MimeSniffer;
use Intervention\MimeSniffer\Types\ImageJpeg;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Drivers\Gd\Encoders\JpegEncoder
 */
class JpegEncoderTest extends TestCase
{
    protected function getTestImage(): Image
    {
        $frame = new Frame(imagecreatetruecolor(3, 2));

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
