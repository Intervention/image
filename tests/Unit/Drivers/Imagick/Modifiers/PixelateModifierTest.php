<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\PixelateModifier;
use Intervention\Image\Tests\ImagickTestCase;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Modifiers\PixelateModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Modifiers\PixelateModifier::class)]
final class PixelateModifierTest extends ImagickTestCase
{
    /**
     * Runs in separate process because of possible imagick-6 memory bug
     */
    #[RunInSeparateProcess]
    #[PreserveGlobalState(true)]
    public function testModify(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertEquals('00aef0', $image->colorAt(2, 2)->toHex());
        $this->assertEquals('ffa601', $image->colorAt(29, 29)->toHex());
        $image->modify(new PixelateModifier(10));
        $this->assertEquals('00aef0', $image->colorAt(2, 2)->toHex());
        $this->assertEquals('ffa601', $image->colorAt(29, 29)->toHex());
    }
}
