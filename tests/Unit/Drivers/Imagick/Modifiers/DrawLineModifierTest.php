<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\DrawLineModifier;
use Intervention\Image\Geometry\Line;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Modifiers\DrawLineModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Modifiers\DrawLineModifier::class)]
final class DrawLineModifierTest extends BaseTestCase
{
    use CanCreateImagickTestImage;

    public function testApply(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertEquals('00aef0', $image->pickColor(14, 14)->toHex());
        $line = new Line(new Point(0, 0), new Point(10, 0), 4);
        $line->setBackgroundColor('b53517');
        $image->modify(new DrawLineModifier($line));
        $this->assertEquals('b53517', $image->pickColor(0, 0)->toHex());
    }
}
