<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\PlaceModifier;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Modifiers\BlurModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Modifiers\PlaceModifier::class)]
final class PlaceModifierTest extends ImagickTestCase
{
    public function testColorChange(): void
    {
        $image = $this->readTestImage('test.jpg');
        $this->assertEquals('febc44', $image->pickColor(300, 25)->toHex());
        $image->modify(new PlaceModifier($this->getTestResourcePath('circle.png'), 'top-right', 0, 0));
        $this->assertEquals('33260e', $image->pickColor(300, 25)->toHex());
    }

    public function testColorChangeOpacity(): void
    {
        $image = $this->readTestImage('test.jpg');
        $this->assertEquals('febc44', $image->pickColor(300, 25)->toHex());
        $image->modify(new PlaceModifier($this->getTestResourcePath('circle.png'), 'top-right', 0, 0, 50));
        $this->assertEquals('987129', $image->pickColor(300, 25)->toHex());
    }
}
