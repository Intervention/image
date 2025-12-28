<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Feature\Gd;

use Intervention\Image\Format;
use Intervention\Image\ImageManager;
use Intervention\Image\Tests\GdTestCase;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('gd')]
class ConvertPngGif extends GdTestCase
{
    public function testConversionKeepsTransparency(): void
    {
        $converted = ImageManager::gd()
            ->decodeFrom(
                binary: $this->readTestImage('circle.png')->encodeUsing(format: Format::GIF)
            );

        $this->assertTransparency($converted->pickColor(0, 0));
        $this->assertColor(4, 2, 4, 1, $converted->pickColor(25, 25), 4);
    }
}
