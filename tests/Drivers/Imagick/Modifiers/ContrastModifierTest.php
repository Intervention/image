<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Imagick\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Requires;
use Intervention\Image\Modifiers\ContrastModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

#[Requires('extension imagick')]
#[CoversClass(\Intervention\Image\Modifiers\ContrastModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Modifiers\ContrastModifier::class)]
final class ContrastModifierTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testApply(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertEquals('00aef0', $image->pickColor(14, 14)->toHex());
        $image->modify(new ContrastModifier(30));
        $this->assertEquals('00fcff', $image->pickColor(14, 14)->toHex());
    }
}
