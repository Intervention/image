<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers;

use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Exceptions\LogicException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
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

    public function testApplyThrowsWhenSpecializedWithoutOverride(): void
    {
        $modifier = new class () extends SpecializableModifier implements SpecializedInterface {
            protected function belongsToDriver(object $driver): bool
            {
                return true;
            }
        };

        $image = Mockery::mock(ImageInterface::class);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('must override apply()');
        $modifier->apply($image);
    }
}
