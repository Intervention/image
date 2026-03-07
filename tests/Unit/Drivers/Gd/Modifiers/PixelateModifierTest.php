<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\PixelateModifier;
use Intervention\Image\Tests\GdTestCase;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Modifiers\PixelateModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Gd\Modifiers\PixelateModifier::class)]
final class PixelateModifierTest extends GdTestCase
{
    public function testModify(): void
    {
        $image = $this->readTestImage('sphere.webp');
        $this->assertEquals('ff7c00', $image->colorAt(2, 2)->toHex());
        $this->assertEquals('ff7a0d', $image->colorAt(29, 29)->toHex());
        $image->modify(new PixelateModifier(10));
        $this->assertEquals('e6a562', $image->colorAt(2, 2)->toHex());
        $this->assertEquals('6f95b2', $image->colorAt(29, 29)->toHex());
    }
}
