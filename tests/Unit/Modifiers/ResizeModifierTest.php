<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Modifiers;

use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Modifiers\ResizeModifier;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ResizeModifier::class)]
final class ResizeModifierTest extends BaseTestCase
{
    public function testConstructorThrowsWithNoArguments(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new ResizeModifier();
    }

    public function testConstructorWithWidth(): void
    {
        $modifier = new ResizeModifier(width: 100);
        $this->assertInstanceOf(ResizeModifier::class, $modifier);
    }

    public function testConstructorWithHeight(): void
    {
        $modifier = new ResizeModifier(height: 100);
        $this->assertInstanceOf(ResizeModifier::class, $modifier);
    }

    public function testConstructorWithBoth(): void
    {
        $modifier = new ResizeModifier(width: 100, height: 200);
        $this->assertInstanceOf(ResizeModifier::class, $modifier);
    }
}
