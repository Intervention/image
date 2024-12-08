<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers;

use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Tests\BaseTestCase;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(SpecializableModifier::class)]
final class SpecializableModifierTest extends BaseTestCase
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
