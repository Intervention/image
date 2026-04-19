<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\DrawEllipseModifier;
use Intervention\Image\Geometry\Ellipse;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Modifiers\DrawEllipseModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Modifiers\DrawEllipseModifier::class)]
final class DrawEllipseModifierTest extends ImagickTestCase
{
    public function testApply(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertEquals('00aef0', $image->pickColor(14, 14)->toHex());
        $drawable = new Ellipse(10, 10, new Point(14, 14));
        $drawable->setBackgroundColor('b53717');
        $image->modify(new DrawEllipseModifier($drawable));
        $this->assertEquals('b53717', $image->pickColor(14, 14)->toHex());
    }

    public function testApplyWithoutBackground(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertEquals('00aef0', $image->pickColor(14, 14)->toHex());
        $drawable = new Ellipse(30, 30, new Point(14, 14));
        $drawable->setBorder('fff', 5);
        $image->modify(new DrawEllipseModifier($drawable));
        $this->assertEquals('ffffff', $image->pickColor(5, 5)->toHex()); // border of circle
        $this->assertEquals('ffa601', $image->pickColor(20, 20)->toHex()); // background of circle
    }
}
