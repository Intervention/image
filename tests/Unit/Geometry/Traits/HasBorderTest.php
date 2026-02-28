<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Geometry\Traits;

use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Geometry\Traits\HasBorder;
use Intervention\Image\Tests\BaseTestCase;

final class HasBorderTest extends BaseTestCase
{
    public function getTestObject(): object
    {
        return new class () {
            use HasBorder;
        };
    }

    public function testSetBorder(): void
    {
        $object = $this->getTestObject();
        $this->assertNull($object->borderColor());
        $this->assertEquals(0, $object->borderSize());
        $this->assertFalse($object->hasBorder());
        $object->setBorder('fff', 10);
        $this->assertEquals('fff', $object->borderColor());
        $this->assertEquals(10, $object->borderSize());
        $this->assertTrue($object->hasBorder());
    }

    public function testSetBorderSize(): void
    {
        $object = $this->getTestObject();
        $this->assertEquals(0, $object->borderSize());
        $object->setBorderSize(10);
        $this->assertEquals(10, $object->borderSize());
    }

    public function testSetBorderSizeNegative(): void
    {
        $object = $this->getTestObject();
        $this->expectException(InvalidArgumentException::class);
        $object->setBorderSize(-1);
    }

    public function testSetBorderSizeZero(): void
    {
        $object = $this->getTestObject();
        $object->setBorderSize(0);
        $this->assertEquals(0, $object->borderSize());
    }

    public function testSetBorderColor(): void
    {
        $object = $this->getTestObject();
        $this->assertNull($object->borderColor());
        $object->setBorderColor('fff');
        $this->assertEquals('fff', $object->borderColor());
        $this->assertFalse($object->hasBorder());
    }

    public function testSetBorderColorWithColorInterface(): void
    {
        $object = $this->getTestObject();
        $color = new RgbColor(255, 0, 0);
        $object->setBorderColor($color);
        $this->assertSame($color, $object->borderColor());
    }

    public function testHasBorder(): void
    {
        $object = $this->getTestObject();
        $this->assertFalse($object->hasBorder());
        $object->setBorderColor('fff');
        $this->assertFalse($object->hasBorder());
        $object->setBorderSize(1);
        $this->assertTrue($object->hasBorder());
    }

    public function testHasBorderWithSizeButNoColor(): void
    {
        $object = $this->getTestObject();
        $object->setBorderSize(5);
        $this->assertFalse($object->hasBorder());
    }

    public function testSetBorderReturnsSelf(): void
    {
        $object = $this->getTestObject();
        $result = $object->setBorder('fff', 1);
        $this->assertSame($object, $result);
    }

    public function testSetBorderSizeReturnsSelf(): void
    {
        $object = $this->getTestObject();
        $result = $object->setBorderSize(1);
        $this->assertSame($object, $result);
    }

    public function testSetBorderColorReturnsSelf(): void
    {
        $object = $this->getTestObject();
        $result = $object->setBorderColor('fff');
        $this->assertSame($object, $result);
    }
}
