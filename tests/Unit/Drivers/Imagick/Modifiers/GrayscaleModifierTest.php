<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\GrayscaleModifier;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Modifiers\GrayscaleModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Modifiers\GrayscaleModifier::class)]
final class GrayscaleModifierTest extends ImagickTestCase
{
    public function testColorChange(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertFalse($image->colorAt(0, 0)->isGrayscale());
        $image->modify(new GrayscaleModifier());
        $this->assertTrue($image->colorAt(0, 0)->isGrayscale());
    }
}
