<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Modifiers\FillTransparentAreasModifier;
use Intervention\Image\Tests\ImagickTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Modifiers\FillTransparentAreasModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Modifiers\FillTransparentAreasModifier::class)]
final class FillTransparentAreasModifierTest extends ImagickTestCase
{
    public function testFillTransparentAreas(): void
    {
        $image = $this->readTestImage('circle.png');
        $this->assertTransparency($image->colorAt(0, 0));
        $image->modify(new FillTransparentAreasModifier(new Color(255, 85, 0)));
        $this->assertColor(255, 85, 0, 255, $image->colorAt(0, 0));
    }

    public function testFillTransparentAreasKeepsAnimationFrames(): void
    {
        $image = $this->readTestImage('animation.gif');
        $count = count($image);
        $this->assertGreaterThan(1, $count);
        $image->modify(new FillTransparentAreasModifier(new Color(255, 85, 0)));
        $this->assertCount($count, $image);
    }
}
