<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\Modifiers\GreyscaleModifier;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\ModifierStack;
use Intervention\Image\Tests\BaseTestCase;
use Mockery;

#[CoversClass(ModifierStack::class)]
final class ModifierStackTest extends BaseTestCase
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

        $modifier1 = Mockery::mock(ModifierInterface::class)->makePartial();
        $modifier1->shouldReceive('apply')->once()->with($image);

        $modifier2 = Mockery::mock(ModifierInterface::class)->makePartial();
        $modifier2->shouldReceive('apply')->once()->with($image);

        $stack = new ModifierStack([$modifier1, $modifier2]);
        $result = $stack->apply($image);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }
}
