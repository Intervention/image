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
}
