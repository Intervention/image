<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Geometry\Traits;

use Intervention\Image\Geometry\Traits\HasBackgroundColor;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(HasBackgroundColor::class)]
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
}
