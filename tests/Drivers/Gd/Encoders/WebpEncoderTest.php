<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd\Encoders;

use Intervention\Image\Collection;
use Intervention\Image\Drivers\Gd\Encoders\WebpEncoder;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Drivers\Gd\Image;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension gd
 */
final class WebpEncoderTest extends TestCase
{
    protected function getTestImage(): Image
    {
        $frame = new Frame(imagecreatetruecolor(3, 2));

        return new Image(new Collection([$frame]));
    }

    public function testEncode(): void
    {
        $image = $this->getTestImage();
        $encoder = new WebpEncoder(75);
        $result = $encoder->encode($image);
        $this->assertMimeType('image/webp', (string) $result);
    }
}
