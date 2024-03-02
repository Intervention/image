<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Encoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Imagick;
use ImagickPixel;
use Intervention\Image\Drivers\Imagick\Core;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Image;
use Intervention\Image\Tests\BaseTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Encoders\JpegEncoder::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Encoders\JpegEncoder::class)]
final class JpegEncoderTest extends BaseTestCase
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
        $encoder = new JpegEncoder(75);
        $result = $encoder->encode($image);
        $this->assertMediaType('image/jpeg', (string) $result);
    }
}
