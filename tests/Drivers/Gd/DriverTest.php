<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd;

use Intervention\Image\Colors\Rgb\Colorspace;
use Intervention\Image\Colors\Rgb\Decoders\HexColorDecoder;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorProcessorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Tests\TestCase;

final class DriverTest extends TestCase
{
    protected Driver $driver;

    protected function setUp(): void
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

    public function testHandleInputImage(): void
    {
        $result = $this->driver->handleInput($this->getTestImagePath('test.jpg'));
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testHandleInputColor(): void
    {
        $result = $this->driver->handleInput('ffffff');
        $this->assertInstanceOf(ColorInterface::class, $result);
    }

    public function testHandleInputObjects(): void
    {
        $result = $this->driver->handleInput('ffffff', [
            new HexColorDecoder()
        ]);
        $this->assertInstanceOf(ColorInterface::class, $result);
    }

    public function testHandleInputClassnames(): void
    {
        $result = $this->driver->handleInput('ffffff', [
            HexColorDecoder::class
        ]);
        $this->assertInstanceOf(ColorInterface::class, $result);
    }

    public function testColorProcessor(): void
    {
        $result = $this->driver->colorProcessor(new Colorspace());
        $this->assertInstanceOf(ColorProcessorInterface::class, $result);
    }
}
