<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Encoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Drivers\Gd\Core;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Image;
use Intervention\Image\Tests\BaseTestCase;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Encoders\JpegEncoder::class)]
#[CoversClass(\Intervention\Image\Drivers\Gd\Encoders\JpegEncoder::class)]
final class JpegEncoderTest extends BaseTestCase
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
        $encoder = new JpegEncoder(75);
        $result = $encoder->encode($image);
        $this->assertMediaType('image/jpeg', (string) $result);
    }
}
