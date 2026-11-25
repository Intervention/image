<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Modifiers\FillModifier;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Modifiers\FillModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Modifiers\FillModifier::class)]
final class FillModifierTest extends ImagickTestCase
{
    public function testFloodFillColor(): void
    {
        $image = $this->readTestImage('blocks.png');
        $this->assertEquals('0000ff', $image->colorAt(420, 270)->toHex());
        $this->assertEquals('ff0000', $image->colorAt(540, 400)->toHex());
        $image->modify(new FillModifier(new Color(204, 204, 204), new Point(540, 400)));
        $this->assertEquals('0000ff', $image->colorAt(420, 270)->toHex());
        $this->assertEquals('cccccc', $image->colorAt(540, 400)->toHex());
    }

    public function testFillAllColor(): void
    {
        $image = $this->readTestImage('blocks.png');
        $this->assertEquals('0000ff', $image->colorAt(420, 270)->toHex());
        $this->assertEquals('ff0000', $image->colorAt(540, 400)->toHex());
        $image->modify(new FillModifier(new Color(204, 204, 204)));
        $this->assertEquals('cccccc', $image->colorAt(420, 270)->toHex());
        $this->assertEquals('cccccc', $image->colorAt(540, 400)->toHex());
    }

    public function testFillAllOpaqueColorCoversTransparentAndAnimatedImages(): void
    {
        foreach (['circle.png', 'animation.gif'] as $filename) {
            $image = $this->readTestImage($filename);
            $image->modify(new FillModifier(new Color(204, 204, 204)));
            for ($index = 0; $index < count($image); $index++) {
                $this->assertColor(204, 204, 204, 255, $image->colorAt(0, 0, $index));
                $this->assertColor(
                    204,
                    204,
                    204,
                    255,
                    $image->colorAt($image->width() - 1, $image->height() - 1, $index),
                );
            }
        }
    }

    public function testFillAllTranslucentColorBlends(): void
    {
        $image = $this->readTestImage('blocks.png');
        $image->modify(new FillModifier(new Color(255, 255, 255, .5)));
        $this->assertColor(255, 128, 128, 255, $image->colorAt(540, 400), tolerance: 1);
        $this->assertColor(128, 128, 255, 255, $image->colorAt(420, 270), tolerance: 1);
    }
}
