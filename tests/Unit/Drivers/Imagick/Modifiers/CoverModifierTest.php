<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\CoverModifier;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Modifiers\CoverModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Modifiers\CoverModifier::class)]
final class CoverModifierTest extends BaseTestCase
{
    use CanCreateImagickTestImage;

    public function testModify(): void
    {
        $image = $this->readTestImage('blocks.png');
        $this->assertEquals(640, $image->width());
        $this->assertEquals(480, $image->height());
        $image->modify(new CoverModifier(100, 100, 'center'));
        $this->assertEquals(100, $image->width());
        $this->assertEquals(100, $image->height());
        $this->assertColor(255, 0, 0, 255, $image->pickColor(90, 90));
        $this->assertColor(0, 255, 0, 255, $image->pickColor(65, 70));
        $this->assertColor(0, 0, 255, 255, $image->pickColor(70, 52));
        $this->assertTransparency($image->pickColor(90, 30));
    }
}
