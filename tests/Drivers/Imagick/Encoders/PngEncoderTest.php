<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Imagick\Encoders;

use Imagick;
use ImagickPixel;
use Intervention\Image\Drivers\Imagick\Encoders\PngEncoder;
use Intervention\Image\Drivers\Imagick\Image;
use Intervention\MimeSniffer\MimeSniffer;
use Intervention\MimeSniffer\Types\ImagePng;
use PHPUnit\Framework\TestCase;

/**
 * @requires extension imagick
 */
final class PngEncoderTest extends TestCase
{
    protected function getTestImage(): Image
    {
        $imagick = new Imagick();
        $imagick->newImage(3, 2, new ImagickPixel('red'), 'jpg');

        return new Image($imagick);
    }

    public function testEncode(): void
    {
        $image = $this->getTestImage();
        $encoder = new PngEncoder(75);
        $result = $encoder->encode($image);
        $this->assertTrue(MimeSniffer::createFromString((string) $result)->matches(new ImagePng()));
    }
}
