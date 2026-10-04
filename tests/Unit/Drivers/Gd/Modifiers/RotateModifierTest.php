<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Modifiers;

use GdImage;
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
    #[DataProvider('rotateKeepsSemiTransparentBackgroundDataProvider')]
    public function testRotateKeepsSemiTransparentBackground(string $background, int $alpha): void
    {
        $image = $this->createTestImage(10, 10);
        $image->modify(new RotateModifier(45, $background));
        $this->assertEquals(-1, imagecolortransparent($image->core()->native()));
        $this->assertColor(255, 255, 0, $alpha, $image->colorAt(0, 0), 1);
        $this->assertColor(255, 255, 0, $alpha, (clone $image)->colorAt(0, 0), 1);
    }

    /**
     * @return array<string, array{string, int}>
     */
    public static function rotateKeepsSemiTransparentBackgroundDataProvider(): array
    {
        return [
            'quarter opaque' => ['ffff0040', 64],
            'three quarters opaque' => ['ffff00c0', 192],
        ];
    }

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

    #[DataProvider('rotateKeepsTransparencyOfTransparentColorDataProvider')]
    public function testRotateKeepsTransparencyOfTransparentColor(float $angle, string $background): void
    {
        // tile.png has a transparent color, right angles do not add new areas
        $image = $this->readTestImage('tile.png');
        $this->assertNotEquals(-1, imagecolortransparent($image->core()->native()));
        $transparent = $this->countFullyTransparentPixels($image->core()->native());
        $this->assertGreaterThan(0, $transparent);

        $image->modify(new RotateModifier($angle, $background));
        $this->assertEquals($transparent, $this->countFullyTransparentPixels($image->core()->native()));
    }

    /**
     * @return array<string, array{float, string}>
     */
    public static function rotateKeepsTransparencyOfTransparentColorDataProvider(): array
    {
        $data = [];
        foreach ([0, 90, 180, 270, 360, -90] as $angle) {
            foreach (['ffffff', 'ff000080'] as $background) {
                $data[$angle . ' degrees on ' . $background] = [$angle, $background];
            }
        }

        return $data;
    }

    private function countFullyTransparentPixels(GdImage $gd): int
    {
        $count = 0;
        for ($y = 0; $y < imagesy($gd); $y++) {
            for ($x = 0; $x < imagesx($gd); $x++) {
                if ((imagecolorat($gd, $x, $y) >> 24 & 0x7F) === 127) {
                    $count++;
                }
            }
        }

        return $count;
    }

    public function testRotateFillsNewAreasWithBackground(): void
    {
        $image = $this->createTestImage(10, 10);
        $image->modify(new RotateModifier(45, 'ff0'));

        // the exact size of the rotated image depends on the libgd version
        $width = $image->width();
        $height = $image->height();
        $this->assertGreaterThan(10, $width);
        $this->assertGreaterThan(10, $height);
        $this->assertColor(255, 255, 0, 255, $image->colorAt(0, 0));
        $this->assertColor(255, 255, 0, 255, $image->colorAt($width - 1, $height - 1));
        $this->assertColor(255, 0, 0, 255, $image->colorAt(intdiv($width, 2), intdiv($height, 2)), 5);
    }

    public function testRotateZeroDegreesWithSemiTransparentBackground(): void
    {
        $image = $this->createTestImage(10, 10);
        $image->modify(new RotateModifier(0, 'ffff0040'));
        $this->assertEquals(-1, imagecolortransparent($image->core()->native()));
        $this->assertColor(255, 0, 0, 255, $image->colorAt(5, 5));
    }

    public function testRotateZeroDegreesKeepsPixels(): void
    {
        $image = $this->readTestImage('circle.png');
        $native = $image->core()->native();
        $image->modify(new RotateModifier(360, 'ffffff00'));
        $this->assertSame($native, $image->core()->native());
        $this->assertTransparency($image->colorAt(0, 0));
        $this->assertEquals(
            $this->readTestImage('circle.png')->colorAt(25, 25)->toHex(),
            $image->colorAt(25, 25)->toHex(),
        );
        $this->assertNotEquals(-1, imagecolortransparent($image->core()->native()));
    }

    public function testRotateZeroDegreesRemovesTransparentColorWithOpaqueBackground(): void
    {
        $image = $this->readTestImage('cats.gif');
        $this->assertNotEquals(-1, imagecolortransparent($image->core()->native()));
        $image->modify(new RotateModifier(0, 'fff'));
        $this->assertEquals(-1, imagecolortransparent($image->core()->native()));
    }
}
