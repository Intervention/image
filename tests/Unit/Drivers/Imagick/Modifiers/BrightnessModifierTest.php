<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\BrightnessModifier;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Modifiers\BrightnessModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Modifiers\BrightnessModifier::class)]
final class BrightnessModifierTest extends ImagickTestCase
{
    public function testApply(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertEquals('00aef0', $image->pickColor(14, 14)->toHex());
        $image->modify(new BrightnessModifier(30));
        $this->assertEquals('39c9ff', $image->pickColor(14, 14)->toHex());
    }
}
