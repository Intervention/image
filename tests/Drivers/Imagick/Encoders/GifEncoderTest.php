<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Imagick\Encoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Requires;
use Imagick;
use ImagickPixel;
use Intervention\Image\Drivers\Imagick\Core;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Encoders\GifEncoder;
use Intervention\Image\Image;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

#[Requires('extension imagick')]
#[CoversClass(\Intervention\Image\Encoders\GifEncoder::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Encoders\GifEncoder::class)]
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

        return new Image(
            new Driver(),
            new Core($imagick)
        );
    }

    public function testEncode(): void
    {
        $image = $this->getTestImage();
        $encoder = new GifEncoder();
        $result = $encoder->encode($image);
        $this->assertMediaType('image/gif', (string) $result);
    }
}
