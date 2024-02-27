<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Requires;
use Intervention\Image\Modifiers\SharpenModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;

#[Requires('extension gd')]
#[CoversClass(\Intervention\Image\Modifiers\SharpenModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Gd\Modifiers\SharpenModifier::class)]
final class SharpenModifierTest extends TestCase
{
    use CanCreateGdTestImage;

    public function testModify(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertEquals('60ab96', $image->pickColor(15, 14)->toHex());
        $image->modify(new SharpenModifier(10));
        $this->assertEquals('4daba7', $image->pickColor(15, 14)->toHex());
    }
}
