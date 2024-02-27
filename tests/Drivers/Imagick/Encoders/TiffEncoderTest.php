<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Imagick\Encoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Requires;
use Imagick;
use ImagickPixel;
use Intervention\Image\Drivers\Imagick\Core;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Encoders\TiffEncoder;
use Intervention\Image\Image;
use Intervention\Image\Tests\TestCase;

#[Requires('extension imagick')]
#[CoversClass(\Intervention\Image\Encoders\TiffEncoder::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Encoders\TiffEncoder::class)]
final class TiffEncoderTest extends TestCase
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
        $encoder = new TiffEncoder();
        $result = $encoder->encode($image);
        $this->assertMediaType('image/tiff', (string) $result);
    }
}
