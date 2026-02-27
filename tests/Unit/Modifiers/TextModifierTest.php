<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Modifiers;

use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\FontInterface;
use Intervention\Image\Modifiers\TextModifier;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Typography\Font;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;
use ReflectionProperty;

#[CoversClass(TextModifier::class)]
final class TextModifierTest extends BaseTestCase
{
    public function testStrokeOffsets(): void
    {
        $modifier = new class ('test', new Point(), new Font()) extends TextModifier
        {
            /**
             * @return array<?Point>
             */
            public function testStrokeOffsets(FontInterface $font): array
            {
                return $this->strokeOffsets($font);
            }
        };

        $this->assertEquals([], $modifier->testStrokeOffsets(new Font()));

        $this->assertEquals([
            new Point(-1, -1),
            new Point(-1, 0),
            new Point(-1, 1),
            new Point(0, -1),
            new Point(0, 0),
            new Point(0, 1),
            new Point(1, -1),
            new Point(1, 0),
            new Point(1, 1),
        ], $modifier->testStrokeOffsets((new Font())->setStrokeWidth(1)));
    }

    public function testStrokeOffsetsWithWidth2(): void
    {
        $modifier = new class ('test', new Point(), new Font()) extends TextModifier
        {
            /**
             * @return array<?Point>
             */
            public function testStrokeOffsets(FontInterface $font): array
            {
                return $this->strokeOffsets($font);
            }
        };

        $offsets = $modifier->testStrokeOffsets((new Font())->setStrokeWidth(2));
        $this->assertCount(25, $offsets);
        $this->assertEquals(new Point(-2, -2), $offsets[0]);
        $this->assertEquals(new Point(2, 2), $offsets[24]);
    }

    public function testConstructor(): void
    {
        $position = new Point(10, 20);
        $font = new Font();
        $modifier = new class ('hello world', $position, $font) extends TextModifier
        {
        };

        $this->assertEquals('hello world', $modifier->text);
        $this->assertSame($position, $modifier->position);
        $this->assertSame($font, $modifier->font);
    }

    public function testTextColorOpaque(): void
    {
        $font = new Font();
        $modifier = new class ('test', new Point(), $font) extends TextModifier
        {
            /**
             * Expose protected textColor method for testing.
             */
            public function testTextColor(): ColorInterface
            {
                return $this->textColor();
            }
        };

        $color = Mockery::mock(ColorInterface::class);
        $color->shouldReceive('isTransparent')->andReturn(false);

        $driver = Mockery::mock(DriverInterface::class);
        $driver->shouldReceive('handleColorInput')->andReturn($color);

        $property = new ReflectionProperty(TextModifier::class, 'driver');
        $property->setValue($modifier, $driver);

        $result = $modifier->testTextColor();
        $this->assertSame($color, $result);
    }

    public function testTextColorTransparentWithStrokeThrowsException(): void
    {
        $font = (new Font())->setStrokeWidth(2);
        $modifier = new class ('test', new Point(), $font) extends TextModifier
        {
            /**
             * Expose protected textColor method for testing.
             */
            public function testTextColor(): ColorInterface
            {
                return $this->textColor();
            }
        };

        $color = Mockery::mock(ColorInterface::class);
        $color->shouldReceive('isTransparent')->andReturn(true);

        $driver = Mockery::mock(DriverInterface::class);
        $driver->shouldReceive('handleColorInput')->andReturn($color);

        $property = new ReflectionProperty(TextModifier::class, 'driver');
        $property->setValue($modifier, $driver);

        $this->expectException(StateException::class);
        $modifier->testTextColor();
    }

    public function testStrokeColorOpaque(): void
    {
        $font = new Font();
        $modifier = new class ('test', new Point(), $font) extends TextModifier
        {
            /**
             * Expose protected strokeColor method for testing.
             */
            public function testStrokeColor(): ColorInterface
            {
                return $this->strokeColor();
            }
        };

        $color = Mockery::mock(ColorInterface::class);
        $color->shouldReceive('isTransparent')->andReturn(false);

        $driver = Mockery::mock(DriverInterface::class);
        $driver->shouldReceive('handleColorInput')->andReturn($color);

        $property = new ReflectionProperty(TextModifier::class, 'driver');
        $property->setValue($modifier, $driver);

        $result = $modifier->testStrokeColor();
        $this->assertSame($color, $result);
    }

    public function testStrokeColorTransparentThrowsException(): void
    {
        $font = new Font();
        $modifier = new class ('test', new Point(), $font) extends TextModifier
        {
            /**
             * Expose protected strokeColor method for testing.
             */
            public function testStrokeColor(): ColorInterface
            {
                return $this->strokeColor();
            }
        };

        $color = Mockery::mock(ColorInterface::class);
        $color->shouldReceive('isTransparent')->andReturn(true);

        $driver = Mockery::mock(DriverInterface::class);
        $driver->shouldReceive('handleColorInput')->andReturn($color);

        $property = new ReflectionProperty(TextModifier::class, 'driver');
        $property->setValue($modifier, $driver);

        $this->expectException(StateException::class);
        $modifier->testStrokeColor();
    }
}
