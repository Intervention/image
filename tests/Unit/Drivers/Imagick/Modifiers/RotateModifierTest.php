<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\RotateModifier;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Modifiers\RotateModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Modifiers\RotateModifier::class)]
final class RotateModifierTest extends ImagickTestCase
{
    public function testRotate(): void
    {
        $image = $this->readTestImage('test.jpg');
        $this->assertEquals(320, $image->width());
        $this->assertEquals(240, $image->height());
        $image->modify(new RotateModifier(90, 'fff'));
        $this->assertEquals(240, $image->width());
        $this->assertEquals(320, $image->height());
    }

    public function testRotateResetsFramePageOffset(): void
    {
        // A non-right-angle rotation grows the virtual canvas; ImageMagick leaves
        // each frame with a negative page offset. That offset mis-composes the
        // animated-AVIF (libheif sequences) writer, leaving the bottom/right
        // transparent, so rotate must reset every frame's page to +0+0.
        $image = $this->readTestImage('animation.gif');
        $image->modify(new RotateModifier(45, 'fff'));

        foreach ($image as $frame) {
            $this->assertSame(0, $frame->offsetLeft());
            $this->assertSame(0, $frame->offsetTop());
        }
    }
}
