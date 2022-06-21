<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Encoders;

use Imagick;
use ImagickPixel;
use Intervention\Image\Collection;
use Intervention\Image\Drivers\Imagick\Encoders\GifEncoder;
use Intervention\Image\Drivers\Imagick\Frame;
use Intervention\Image\Drivers\Imagick\Image;
use Intervention\Image\Tests\TestCase;
use Intervention\MimeSniffer\MimeSniffer;
use Intervention\MimeSniffer\Types\ImageGif;

/**
 * @requires extension imagick
 * @covers \Intervention\Image\Drivers\Imagick\Encoders\GifEncoder
 */
class GifEncoderTest extends TestCase
{
    protected function getTestImage(): Image
    {
        $imagick = new Imagick();

        $frame = new Imagick();
        $frame->newImage(30, 20, new ImagickPixel('red'), 'png');
        $frame->setImageDelay(50);
        $imagick->addImage($frame);

        $frame = new Imagick();
        $frame->newImage(30, 20, new ImagickPixel('green'), 'png');
        $frame->setImageDelay(50);
        $imagick->addImage($frame);

        $frame = new Imagick();
        $frame->newImage(30, 20, new ImagickPixel('blue'), 'png');
        $frame->setImageDelay(50);
        $imagick->addImage($frame);

        return new Image($imagick);
    }

    public function testEncode(): void
    {
        $image = $this->getTestImage();
        $encoder = new GifEncoder();
        $result = $encoder->encode($image);
        $this->assertTrue(MimeSniffer::createFromString($result)->matches(new ImageGif()));
    }
}
