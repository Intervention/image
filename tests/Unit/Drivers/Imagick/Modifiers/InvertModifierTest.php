<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\InvertModifier;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Modifiers\InvertModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Modifiers\InvertModifier::class)]
final class InvertModifierTest extends ImagickTestCase
{
    public function testApply(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertEquals('00aef0', $image->pickColor(0, 0)->toHex());
        $this->assertEquals('ffa601', $image->pickColor(25, 25)->toHex());
        $image->modify(new InvertModifier());
        $this->assertEquals('ff510f', $image->pickColor(0, 0)->toHex());
        $this->assertEquals('0059fe', $image->pickColor(25, 25)->toHex());
    }
}
