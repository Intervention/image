<?php

namespace Intervention\Image\Tests\Drivers\Gd\Encoders;

use Intervention\Image\Collection;
use Intervention\Image\Drivers\Gd\Encoders\PngEncoder;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Drivers\Gd\Image;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Drivers\Gd\Encoders\PngEncoder
 */
class PngEncoderTest extends TestCase
{
    use CanCreateGdTestImage;

    protected function getTestImage(): Image
    {
        $frame = new Frame(imagecreatetruecolor(3, 2));

        return new Image(new Collection([$frame]));
    }

    public function testEncode(): void
    {
        $image = $this->getTestImage();
        $encoder = new PngEncoder();
        $result = $encoder->encode($image);
        $this->assertMimeType('image/png', (string) $result);
    }

    public function testEncodeReduced(): void
    {
        $image = $this->createTestImage('tile.png');
        $gd = $image->frame()->core();
        $this->assertEquals(3, imagecolorstotal($gd));
        $encoder = new PngEncoder(2);
        $result = $encoder->encode($image);
        $gd = imagecreatefromstring((string) $result);
        $this->assertEquals(2, imagecolorstotal($gd));
    }
}
