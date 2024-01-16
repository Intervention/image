<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd;

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Tests\TestCase;

class DriverTest extends TestCase
{
    protected Driver $driver;

    public function setUp(): void
    {
        $this->driver = new Driver();
    }

    public function testId(): void
    {
        $this->assertEquals('GD', $this->driver->id());
    }

    public function testCreateImage(): void
    {
        $image = $this->driver->createImage(3, 2);
        $this->assertInstanceOf(ImageInterface::class, $image);
        $this->assertEquals(3, $image->width());
        $this->assertEquals(2, $image->height());
    }

    public function testCreateAnimation(): void
    {
        $image = $this->driver->createAnimation(function ($animation) {
            $animation->add($this->getTestImagePath('red.gif'), .25);
            $animation->add($this->getTestImagePath('green.gif'), .25);
        })->setLoops(5);
        $this->assertInstanceOf(ImageInterface::class, $image);

        $this->assertEquals(16, $image->width());
        $this->assertEquals(16, $image->height());
        $this->assertEquals(5, $image->loops());
        $this->assertEquals(2, $image->count());
    }
}
