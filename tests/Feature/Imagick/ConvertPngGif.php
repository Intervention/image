<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Feature\Imagick;

use Intervention\Image\ImageManager;
use Intervention\Image\Tests\ImagickTestCase;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('imagick')]
class ConvertPngGif extends ImagickTestCase
{
    public function testConversionKeepsTransparency(): void
    {
        $converted = ImageManager::imagick()
            ->read(
                $this->readTestImage('circle.png')->toGif()
            );

        $this->assertTransparency($converted->pickColor(0, 0));
        $this->assertColor(4, 2, 4, 255, $converted->pickColor(25, 25), 4);
    }
}
