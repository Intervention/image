<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Modifiers;

use Intervention\Image\Colors\Rgb\Colorspace;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Modifiers\ColorspaceModifier;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ColorspaceModifier::class)]
final class ColorspaceModifierTest extends BaseTestCase
{
    public function testTargetColorspace(): void
    {
        $modifier = new ColorspaceModifier(new Colorspace());
        $this->assertInstanceOf(ColorspaceInterface::class, $modifier->targetColorspace());

        $modifier = new ColorspaceModifier('rgb');
        $this->assertInstanceOf(ColorspaceInterface::class, $modifier->targetColorspace());

        $modifier = new ColorspaceModifier('cmyk');
        $this->assertInstanceOf(ColorspaceInterface::class, $modifier->targetColorspace());

        $modifier = new ColorspaceModifier('test');
        $this->expectException(NotSupportedException::class);
        $modifier->targetColorspace();
    }
}
