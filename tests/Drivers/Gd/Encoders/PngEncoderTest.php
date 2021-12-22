<?php

namespace Intervention\Image\Tests\Drivers\Gd\Encoders;

use Intervention\Image\Collection;
use Intervention\Image\Drivers\Gd\Encoders\PngEncoder;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Drivers\Gd\Image;
use Intervention\Image\Tests\TestCase;
use Intervention\MimeSniffer\MimeSniffer;
use Intervention\MimeSniffer\Types\ImagePng;

/**
 * @covers \Intervention\Image\Drivers\Gd\Encoders\PngEncoder
 */
class PngEncoderTest extends TestCase
{
    protected function getTestImage(): Image
    {
        $frame = new Frame(imagecreatetruecolor(3, 2));

        return new Image(new Collection([$frame]));
    }

    public function testEncode(): void
    {
        $image = $this->getTestImage();
        $encoder = new PngEncoder();
        $result = $encoder->encode($image);
        $this->assertTrue(MimeSniffer::createFromString($result)->matches(new ImagePng));
    }
}
