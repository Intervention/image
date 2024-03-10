<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\GammaModifier;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Modifiers\GammaModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Modifiers\GammaModifier::class)]
final class GammaModifierTest extends ImagickTestCase
{
    public function testModifier(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertEquals('00aef0', $image->pickColor(0, 0)->toHex());
        $image->modify(new GammaModifier(2.1));
        $this->assertEquals('00d5f8', $image->pickColor(0, 0)->toHex());
    }
}
