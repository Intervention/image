<?php

namespace Intervention\Image\Tests;

use Intervention\Image\Drivers\Gd\Modifiers\GreyscaleModifier;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\ModifierStack;
use Mockery;

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

    public function testApply(): void
    {
        $image = Mockery::mock(ImageInterface::class);

        $modifier1 = Mockery::mock(AbstractColor::class)->makePartial();
        $modifier1->shouldReceive('apply')->once()->with($image);

        $modifier2 = Mockery::mock(AbstractColor::class)->makePartial();
        $modifier2->shouldReceive('apply')->once()->with($image);

        $stack = new ModifierStack([$modifier1, $modifier2]);
        $result = $stack->apply($image);
        $this->assertInstanceOf(ImageInterface::class, $image);
    }
}
