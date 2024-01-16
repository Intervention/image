<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd\Encoders;

use Intervention\Image\Drivers\Gd\Core;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\BmpEncoder;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Image;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Encoders\BmpEncoder
 * @covers \Intervention\Image\Drivers\Gd\Encoders\BmpEncoder
 */
class BmpEncoderTest extends TestCase
{
    protected function getTestImage(): Image
    {
        return new Image(
            new Driver(),
            new Core([
                new Frame(imagecreatetruecolor(3, 2))
            ])
        );
    }

    public function testEncode(): void
    {
        $image = $this->getTestImage();
        $encoder = new BmpEncoder();
        $result = $encoder->encode($image);
        $this->assertMediaType(['image/bmp', 'image/x-ms-bmp'], (string) $result);
    }
}
