<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Feature\Imagick;

use Intervention\Image\Tests\ImagickTestCase;

class CropResizePngTest extends ImagickTestCase
{
    public function testCropResizePng(): void
    {
        $image = $this->readTestImage('tile.png');
        $image->crop(100, 100);
        $image->resize(200, 200);
        $this->assertTransparency($image->pickColor(7, 22));
        $this->assertTransparency($image->pickColor(22, 7));
    }
}
