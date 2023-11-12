<?php

namespace Intervention\Image\Tests\Drivers\Gd\Encoders;

use Intervention\Image\Collection;
use Intervention\Image\Drivers\Gd\Encoders\AvifEncoder;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Drivers\Gd\Image;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Drivers\Gd\Encoders\AvifEncoder
 */
class AvifEncoderTest extends TestCase
{
    protected function getTestImage(): Image
    {
        return new Image(new Collection([
            new Frame(imagecreatetruecolor(3, 2))
        ]));
    }

    public function testEncode(): void
    {
        $image = $this->getTestImage();
        $encoder = new AvifEncoder(10);
        $result = $encoder->encode($image);
        $this->assertMimeType('image/avif', (string) $result);
    }
}
