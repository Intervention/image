<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Modifiers;

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Drivers\Gd\Modifiers\TextModifier;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\ColorInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Tests\GdTestCase;
use Intervention\Image\Typography\Font;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Modifiers\TextModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Gd\Modifiers\TextModifier::class)]
final class TextModifierTest extends GdTestCase
{
    public function testTextColor(): void
    {
        $font = (new Font())->setColor('ff0055');

        $modifier = new class ('test', new Point(), $font) extends TextModifier
        {
            public function test()
            {
                return $this->textColor();
            }
        };

        $modifier->setDriver(new Driver());

        $this->assertInstanceOf(ColorInterface::class, $modifier->test());
    }
}
