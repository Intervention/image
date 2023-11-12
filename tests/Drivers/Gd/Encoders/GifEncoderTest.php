<?php

namespace Intervention\Image\Tests\Drivers\Gd\Encoders;

use Intervention\Image\Collection;
use Intervention\Image\Drivers\Gd\Encoders\GifEncoder;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Drivers\Gd\Image;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Drivers\Gd\Encoders\GifEncoder
 */
class GifEncoderTest extends TestCase
{
    use CanCreateGdTestImage;

    protected function getTestImage(): Image
    {
        $gd1 = imagecreatetruecolor(30, 20);
        imagefill($gd1, 0, 0, imagecolorallocate($gd1, 255, 0, 0));
        $gd2 = imagecreatetruecolor(30, 20);
        imagefill($gd2, 0, 0, imagecolorallocate($gd2, 0, 255, 0));
        $gd3 = imagecreatetruecolor(30, 20);
        imagefill($gd3, 0, 0, imagecolorallocate($gd3, 0, 0, 255));
        $frame1 = new Frame($gd1);
        $frame1->setDelay(1);
        $frame2 = new Frame($gd2);
        $frame2->setDelay(.2);
        $frame3 = new Frame($gd3);
        $frame3->setDelay(1);

        return new Image(new Collection([$frame1, $frame2, $frame3]));
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
        $gd = $image->frame()->core();
        $this->assertEquals(15, imagecolorstotal($gd));
        $encoder = new GifEncoder(2);
        $result = $encoder->encode($image);
        $gd = imagecreatefromstring((string) $result);
        $this->assertEquals(2, imagecolorstotal($gd));
    }
}
