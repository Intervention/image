<?php

namespace Intervention\Image\Tests;

use Intervention\Image\Drivers\Gd\Modifiers\GreyscaleModifier;
use Intervention\Image\ModifierStack;

/**
 * @covers \Intervention\Image\ModifierStack
 */
class ModifierStackTest extends TestCase
{
    public function testConstructor(): void
    {
        $stack = new ModifierStack([]);
        $this->assertInstanceOf(ModifierStack::class, $stack);
    }

    public function testPush(): void
    {
        $stack = new ModifierStack([]);
        $result = $stack->push(new GreyscaleModifier());
        $this->assertInstanceOf(ModifierStack::class, $result);
    }
}
