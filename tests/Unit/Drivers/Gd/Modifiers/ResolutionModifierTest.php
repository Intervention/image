<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Modifiers\ResolutionModifier;
use Intervention\Image\Tests\GdTestCase;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Modifiers\ResolutionModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Gd\Modifiers\ResolutionModifier::class)]
final class ResolutionModifierTest extends GdTestCase
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
}
