<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\CropModifier;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Modifiers\CropModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Modifiers\CropModifier::class)]
final class CropModifierTest extends BaseTestCase
{
    use CanCreateImagickTestImage;

    public function testModify(): void
    {
        $image = $this->readTestImage('blocks.png');
        $image = $image->modify(new CropModifier(200, 200, 0, 0, 'ffffff', 'bottom-right'));
        $this->assertEquals(200, $image->width());
        $this->assertEquals(200, $image->height());
        $this->assertColor(255, 0, 0, 255, $image->pickColor(5, 5));
        $this->assertColor(255, 0, 0, 255, $image->pickColor(100, 100));
        $this->assertColor(255, 0, 0, 255, $image->pickColor(190, 190));
    }

    public function testModifyExtend(): void
    {
        $image = $this->readTestImage('blocks.png');
        $image = $image->modify(new CropModifier(800, 100, -10, -10, 'ff0000', 'top-left'));
        $this->assertEquals(800, $image->width());
        $this->assertEquals(100, $image->height());
        $this->assertColor(255, 0, 0, 255, $image->pickColor(9, 9));
        $this->assertColor(0, 0, 255, 255, $image->pickColor(16, 16));
        $this->assertColor(0, 0, 255, 255, $image->pickColor(445, 16));
        $this->assertTransparency($image->pickColor(460, 16));
    }
}
