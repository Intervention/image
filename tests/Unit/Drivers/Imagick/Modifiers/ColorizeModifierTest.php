<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\ColorizeModifier;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Modifiers\ColorizeModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Modifiers\ColorizeModifier::class)]
final class ColorizeModifierTest extends ImagickTestCase
{
    public function testModify(): void
    {
        $image = $this->readTestImage('tile.png');
        $image = $image->modify(new ColorizeModifier(100, -100, -100));
        $this->assertColor(251, 0, 0, 255, $image->pickColor(5, 5));
        $this->assertColor(239, 0, 0, 255, $image->pickColor(15, 15));
    }
}
