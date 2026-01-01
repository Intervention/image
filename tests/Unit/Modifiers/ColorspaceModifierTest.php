<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Modifiers;

use Intervention\Image\Colors\Cmyk\Colorspace as Cmyk;
use Intervention\Image\Colors\Rgb\Colorspace as Rgb;
use Intervention\Image\Colors\Hsl\Colorspace as Hsl;
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
        $this->assertInstanceOf(
            Rgb::class,
            $this->colorspaceModifier(new Rgb())->getTargetColorspace(),
        );

        $this->assertInstanceOf(
            Rgb::class,
            $this->colorspaceModifier('rgb')->getTargetColorspace(),
        );

        $this->assertInstanceOf(
            Cmyk::class,
            $this->colorspaceModifier('cmyk')->getTargetColorspace(),
        );

        $this->assertInstanceOf(
            Hsl::class,
            $this->colorspaceModifier('hsl')->getTargetColorspace(),
        );
    }

    public function testTargetColorspaceFail(): void
    {
        $this->expectException(NotSupportedException::class);
        $this->colorspaceModifier('not_existing')->getTargetColorspace();
    }

    private function colorspaceModifier(string|ColorspaceInterface $colorspace): ColorspaceModifier
    {
        return new class ($colorspace) extends ColorspaceModifier
        {
            public function __construct(string|ColorspaceInterface $colorspace)
            {
                return parent::__construct($colorspace);
            }

            public function getTargetColorspace(): ColorspaceInterface
            {
                return parent::targetColorspace();
            }
        };
    }
}
