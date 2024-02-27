<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Requires;
use Intervention\Image\Modifiers\GreyscaleModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

#[Requires('extension gd')]
#[CoversClass(\Intervention\Image\Modifiers\GreyscaleModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Gd\Modifiers\GreyscaleModifier::class)]
final class GreyscaleModifierTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testColorChange(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertFalse($image->pickColor(0, 0)->isGreyscale());
        $image->modify(new GreyscaleModifier());
        $this->assertTrue($image->pickColor(0, 0)->isGreyscale());
    }
}
