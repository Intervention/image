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
        $imagick1 = new Imagick();
        $imagick1->newImage(30, 20, new ImagickPixel('red'), 'png');
        $frame1 = new Frame($imagick1);
        $frame1->setDelay(50);
        $imagick2 = new Imagick();
        $imagick2->newImage(30, 20, new ImagickPixel('green'), 'png');
        $frame2 = new Frame($imagick2);
        $frame2->setDelay(50);
        $imagick3 = new Imagick();
        $imagick3->newImage(30, 20, new ImagickPixel('blue'), 'png');
        $frame3 = new Frame($imagick3);
        $frame3->setDelay(50);

        return new Image(new Collection([$frame1, $frame2, $frame3]));
    }

    public function testEncode(): void
    {
        $image = $this->getTestImage();
        $encoder = new GifEncoder();
        $result = $encoder->encode($image);
        $this->assertTrue(MimeSniffer::createFromString($result)->matches(new ImageGif()));
    }
}
