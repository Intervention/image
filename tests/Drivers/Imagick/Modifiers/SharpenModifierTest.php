<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Requires;
use Intervention\Image\Modifiers\SharpenModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

#[Requires('extension imagick')]
#[CoversClass(\Intervention\Image\Modifiers\SharpenModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Modifiers\SharpenModifier::class)]
final class SharpenModifierTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testModify(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertEquals('60ab96', $image->pickColor(15, 14)->toHex());
        $image->modify(new SharpenModifier(10));
        $this->assertEquals('4faca6', $image->pickColor(15, 14)->toHex());
    }
}
