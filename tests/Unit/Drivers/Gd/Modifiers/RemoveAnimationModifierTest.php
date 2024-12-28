<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Modifiers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Exceptions\InputException;
use Intervention\Image\Modifiers\RemoveAnimationModifier;
use Intervention\Image\Tests\GdTestCase;

#[RequiresPhpExtension('gd')]
#[CoversClass(\Intervention\Image\Modifiers\RemoveAnimationModifier::class)]
#[CoversClass(\Intervention\Image\Drivers\Gd\Modifiers\RemoveAnimationModifier::class)]
final class RemoveAnimationModifierTest extends GdTestCase
{
    public function testApply(): void
    {
        $image = $this->readTestImage('animation.gif');
        $this->assertEquals(8, count($image));
        $result = $image->modify(new RemoveAnimationModifier(2));
        $this->assertEquals(1, count($image));
        $this->assertEquals(1, count($result));
    }

    public function testApplyPercent(): void
    {
        $image = $this->readTestImage('animation.gif');
        $this->assertEquals(8, count($image));
        $result = $image->modify(new RemoveAnimationModifier('20%'));
        $this->assertEquals(1, count($image));
        $this->assertEquals(1, count($result));
    }

    public function testApplyNonAnimated(): void
    {
        $image = $this->readTestImage('test.jpg');
        $this->assertEquals(1, count($image));
        $result = $image->modify(new RemoveAnimationModifier());
        $this->assertEquals(1, count($image));
        $this->assertEquals(1, count($result));
    }

    public function testApplyInvalid(): void
    {
        $image = $this->readTestImage('animation.gif');
        $this->expectException(InputException::class);
        $image->modify(new RemoveAnimationModifier('test'));
    }
}
