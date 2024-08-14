<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Feature\Gd;

use Intervention\Image\ImageManager;
use Intervention\Image\Tests\GdTestCase;

class ConvertPngGif extends GdTestCase
{
    public function testConversionKeepsTransparency(): void
    {
        $converted = ImageManager::gd()
            ->read(
                $this->readTestImage('circle.png')->toGif()
            );

        $this->assertTransparency($converted->pickColor(0, 0));
        $this->assertColor(4, 2, 4, 255, $converted->pickColor(25, 25), 4);
    }
}
