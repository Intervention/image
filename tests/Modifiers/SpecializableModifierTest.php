<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Modifiers;

use Intervention\Image\Modifiers\SpecializableModifier;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Tests\TestCase;
use Mockery;

class SpecializableModifierTest extends TestCase
{
    public function testApply(): void
    {
        $modifier = Mockery::mock(SpecializableModifier::class)->makePartial();
        $image = Mockery::mock(ImageInterface::class);
        $image->shouldReceive('modify')->andReturn($image);

        $result = $modifier->apply($image);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }
}
