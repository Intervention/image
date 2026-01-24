<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Feature\Gd;

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;
use Intervention\Image\Image;
use Intervention\Image\Tests\GdTestCase;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('gd')]
class ConvertPngGif extends GdTestCase
{
    public function testConversionKeepsTransparency(): void
    {
        $converted = Image::usingDriver(Driver::class)->fromBinary(
            $this->readTestImage('circle.png')->encodeUsingFormat(Format::GIF)
        );

        $this->assertTransparency($converted->colorAt(0, 0));
        $this->assertColor(4, 2, 4, 255, $converted->colorAt(25, 25), 4);
    }
}
