<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\ResolutionModifier;
use Intervention\Image\Tests\ImagickTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Modifiers\ResolutionModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Modifiers\ResolutionModifier::class)]
final class ResolutionModifierTest extends ImagickTestCase
{
    public function testResolutionChange(): void
    {
        $image = $this->readTestImage('test.jpg');
        $this->assertEquals(72.0, $image->resolution()->x());
        $this->assertEquals(72.0, $image->resolution()->y());
        $image->modify(new ResolutionModifier(1, 2));
        $this->assertEquals(1.0, $image->resolution()->x());
        $this->assertEquals(2.0, $image->resolution()->y());
    }

    public function testResolutionChangeAnimated(): void
    {
        $image = $this->createTestAnimation();
        $image->modify(new ResolutionModifier(300, 150));

        foreach ($image as $key => $frame) {
            $resolution = $frame->native()->getImageResolution();
            $this->assertEquals(300.0, $resolution['x'], 'Frame ' . $key . ' kept its resolution');
            $this->assertEquals(150.0, $resolution['y'], 'Frame ' . $key . ' kept its resolution');
        }
    }
}
