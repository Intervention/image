<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\AbstractDrawModifier;
use Intervention\Image\Modifiers\DrawRectangleModifier;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Modifiers\DrawRectangleModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Modifiers\DrawRectangleModifier::class)]
#[CoversClass(AbstractDrawModifier::class)]
final class DrawRectangleModifierTest extends ImagickTestCase
{
    public function testApply(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertEquals('00aef0', $image->colorAt(14, 14)->toHex());
        $rectangle = new Rectangle(300, 200, new Point(14, 14));
        $rectangle->setBackgroundColor('ffffff');
        $image->modify(new DrawRectangleModifier($rectangle));
        $this->assertEquals('ffffff', $image->colorAt(14, 14)->toHex());
    }

    public function testApplyWithBorder(): void
    {
        $image = $this->readTestImage('trim.png');
        $rectangle = new Rectangle(10, 10, new Point(0, 0));
        $rectangle->setBackgroundColor('ffffff');
        $rectangle->setBorder('ff0000', 1);
        $image->modify(new DrawRectangleModifier($rectangle));
        $this->assertEquals('ff0000', $image->colorAt(0, 0)->toHex());
    }
}
