<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Imagick\Encoders;

use Imagick;
use ImagickPixel;
use Intervention\Image\Drivers\Imagick\Core;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Encoders\Jpeg2000Encoder;
use Intervention\Image\Image;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension imagick
 * @covers \Intervention\Image\Encoders\Jpeg2000Encoder
 * @covers \Intervention\Image\Drivers\Imagick\Encoders\Jpeg2000Encoder
 */
class Jpeg2000EncoderTest extends TestCase
{
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
        $encoder = new Jpeg2000Encoder(75);
        $result = $encoder->encode($image);
        $this->assertMediaType('image/jp2', (string) $result);
    }
}
