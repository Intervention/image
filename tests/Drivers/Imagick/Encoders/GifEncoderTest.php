<?php

namespace Intervention\Image\Tests\Drivers\Imagick\Encoders;

use Imagick;
use ImagickPixel;
use Intervention\Image\Drivers\Imagick\Encoders\GifEncoder;
use Intervention\Image\Drivers\Imagick\Image;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

/**
 * @requires extension imagick
 * @covers \Intervention\Image\Drivers\Imagick\Encoders\GifEncoder
 */
class GifEncoderTest extends TestCase
{
    use CanCreateImagickTestImage;

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
        $this->assertMimeType('image/gif', (string) $result);
    }

    public function testEncodeReduced(): void
    {
        $image = $this->createTestImage('gradient.gif');
        $imagick = $image->frame()->core();
        $this->assertEquals(15, $imagick->getImageColors());
        $encoder = new GifEncoder(2);
        $result = $encoder->encode($image);
        $imagick = new Imagick();
        $imagick->readImageBlob((string) $result);
        $this->assertEquals(2, $imagick->getImageColors());
    }
}
