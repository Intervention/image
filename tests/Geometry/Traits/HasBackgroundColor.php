<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Geometry\Traits;

use Intervention\Image\Geometry\Traits\HasBackgroundColor;
use Intervention\Image\Tests\TestCase;

class HasBackgroundColorTest extends TestCase
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
}
