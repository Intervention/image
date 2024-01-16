<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd\Encoders;

use Intervention\Image\Drivers\Gd\Core;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\GifEncoder;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Image;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Encoders\GifEncoder
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

        return new Image(
            new Driver(),
            new Core([$frame1, $frame2, $frame3])
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
