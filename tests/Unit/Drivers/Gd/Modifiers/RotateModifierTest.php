<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\RotateModifier;
use Intervention\Image\Tests\GdTestCase;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Modifiers\RotateModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Gd\Modifiers\RotateModifier::class)]
final class RotateModifierTest extends GdTestCase
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

    public function testRotateKeepsTransparentPixels(): void
    {
        $image = $this->readTestImage('tile.png');
        $this->assertTransparency($image->colorAt(12, 5));
        $this->assertTransparency($image->colorAt(5, 12));
        $image->modify(new RotateModifier(90, 'fff'));
        $this->assertEquals(16, $image->width());
        $this->assertEquals(16, $image->height());
        $this->assertTransparency($image->colorAt(5, 5));
        $this->assertTransparency($image->colorAt(12, 12));
        $this->assertEquals('445160', $image->colorAt(5, 12)->toHex());
        $this->assertEquals('b4e000', $image->colorAt(12, 5)->toHex());
    }

    #[DataProvider('rotateKeepsFullyTransparentPixelsDataProvider')]
    public function testRotateKeepsFullyTransparentPixels(float $angle): void
    {
        $image = $this->readTestImage('circle.png');
        $this->assertTransparency($image->colorAt(0, 0));
        $image->modify(new RotateModifier($angle, 'fff'));
        $this->assertTransparency($image->colorAt(0, 0));
        $this->assertTransparency($image->colorAt($image->width() - 1, $image->height() - 1));
    }

    /**
     * @return array<string, array{float}>
     */
    public static function rotateKeepsFullyTransparentPixelsDataProvider(): array
    {
        return [
            '0 degrees' => [0],
            '90 degrees' => [90],
            '180 degrees' => [180],
            '270 degrees' => [270],
        ];
    }

    public function testRotateFillsNewAreasWithBackground(): void
    {
        $image = $this->createTestImage(10, 10);
        $image->modify(new RotateModifier(45, 'ff0'));
        $this->assertEquals(13, $image->width());
        $this->assertEquals(14, $image->height());
        $this->assertColor(255, 255, 0, 255, $image->colorAt(0, 0));
        $this->assertColor(255, 255, 0, 255, $image->colorAt(12, 13));
        $this->assertColor(255, 0, 0, 255, $image->colorAt(6, 6), 5);
    }
}
