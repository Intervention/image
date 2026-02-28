<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Geometry\Traits;

use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Geometry\Traits\HasBackgroundColor;
use Intervention\Image\Tests\BaseTestCase;

final class HasBackgroundColorTest extends BaseTestCase
{
    public function getTestObject(): object
    {
        return new class () {
            use HasBackgroundColor;
        };
    }

    public function testSetGetBackgroundColor(): void
    {
        $object = $this->getTestObject();
        $this->assertNull($object->backgroundColor());
        $this->assertFalse($object->hasBackgroundColor());
        $object->setBackgroundColor('fff');
        $this->assertEquals('fff', $object->backgroundColor());
        $this->assertTrue($object->hasBackgroundColor());
    }

    public function testSetBackgroundColorWithColorInterface(): void
    {
        $object = $this->getTestObject();
        $color = new RgbColor(255, 0, 0);
        $object->setBackgroundColor($color);
        $this->assertSame($color, $object->backgroundColor());
        $this->assertTrue($object->hasBackgroundColor());
    }

    public function testSetBackgroundColorReturnsSelf(): void
    {
        $object = $this->getTestObject();
        $result = $object->setBackgroundColor('fff');
        $this->assertSame($object, $result);
    }
}
