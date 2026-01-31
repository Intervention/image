<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Feature\Imagick;

use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Format;
use Intervention\Image\ImageManager;
use Intervention\Image\Tests\ImagickTestCase;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('imagick')]
class ConvertPngGif extends ImagickTestCase
{
    public function testConversionKeepsTransparency(): void
    {
        $converted = ImageManager::usingDriver(Driver::class)->decodeBinary(
            $this->readTestImage('circle.png')->encodeUsingFormat(Format::GIF)
        );

        $this->assertTransparency($converted->colorAt(0, 0));
        $this->assertColor(4, 2, 4, 255, $converted->colorAt(25, 25), 4);
    }
}
