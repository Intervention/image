<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use Intervention\Image\Interfaces\ImageInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\PixelateModifier;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Modifiers\PixelateModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Modifiers\PixelateModifier::class)]
final class PixelateModifierTest extends ImagickTestCase
{
    public function testModify(): void
    {
        $image = $this->readTestImage('trim.png');
        $result = $image->modify(new PixelateModifier(10));
        $this->assertInstanceOf(ImageInterface::class, $result);
    }
}
