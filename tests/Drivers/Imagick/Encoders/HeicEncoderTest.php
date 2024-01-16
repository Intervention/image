<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Imagick\Encoders;

use Imagick;
use ImagickPixel;
use Intervention\Image\Drivers\Imagick\Core;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Encoders\HeicEncoder;
use Intervention\Image\Image;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

/**
 * @requires extension imagick
 * @covers \Intervention\Image\Encoders\HeicEncoder
 * @covers \Intervention\Image\Drivers\Imagick\Encoders\HeicEncoder
 */
final class HeicEncoderTest extends TestCase
{
    use CanCreateImagickTestImage;

    protected function getTestImage(): Image
    {
        $imagick = new Imagick();
        $imagick->newImage(3, 2, new ImagickPixel('red'), 'jpg');

        return new Image(
            new Driver(),
            new Core($imagick)
        );
    }

    public function testEncode(): void
    {
        $image = $this->getTestImage();
        $encoder = new HeicEncoder(75);
        $result = $encoder->encode($image);
        $this->assertMediaType('image/heic', (string) $result);
    }
}
