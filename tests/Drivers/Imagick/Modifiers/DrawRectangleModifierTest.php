<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\DrawRectangleModifier;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Modifiers\DrawRectangleModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Modifiers\DrawRectangleModifier::class)]
final class DrawRectangleModifierTest extends BaseTestCase
{
    use CanCreateImagickTestImage;

    public function testApply(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertEquals('00aef0', $image->pickColor(14, 14)->toHex());
        $rectangle = new Rectangle(300, 200, new Point(14, 14));
        $rectangle->setBackgroundColor('ffffff');
        $image->modify(new DrawRectangleModifier($rectangle));
        $this->assertEquals('ffffff', $image->pickColor(14, 14)->toHex());
    }
}
