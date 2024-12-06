<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Geometry\Traits;

use Intervention\Image\Geometry\Traits\HasBorder;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(HasBorder::class)]
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

    public function testSetBorderColor(): void
    {
        $object = $this->getTestObject();
        $this->assertNull($object->borderColor());
        $object->setBorderColor('fff');
        $this->assertEquals('fff', $object->borderColor());
        $this->assertFalse($object->hasBorder());
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
}
