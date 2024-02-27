<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd;

use Intervention\Image\Drivers\Gd\FontProcessor;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Typography\Font;
use Intervention\Image\Typography\TextBlock;

final class FontProcessorTest extends TestCase
{
    public function testBoxSizeGdOne(): void
    {
        $processor = new FontProcessor();
        $size = $processor->boxSize('test', new Font());
        $this->assertInstanceOf(SizeInterface::class, $size);
        $this->assertEquals(16, $size->width());
        $this->assertEquals(8, $size->height());
    }

    public function testBoxSizeGdTwo(): void
    {
        $processor = new FontProcessor();
        $size = $processor->boxSize('test', new Font('2'));
        $this->assertInstanceOf(SizeInterface::class, $size);
        $this->assertEquals(24, $size->width());
        $this->assertEquals(14, $size->height());
    }

    public function testBoxSizeGdThree(): void
    {
        $processor = new FontProcessor();
        $size = $processor->boxSize('test', new Font('3'));
        $this->assertInstanceOf(SizeInterface::class, $size);
        $this->assertEquals(28, $size->width());
        $this->assertEquals(14, $size->height());
    }

    public function testBoxSizeGdFour(): void
    {
        $processor = new FontProcessor();
        $size = $processor->boxSize('test', new Font('4'));
        $this->assertInstanceOf(SizeInterface::class, $size);
        $this->assertEquals(32, $size->width());
        $this->assertEquals(16, $size->height());
    }

    public function testBoxSizeGdFive(): void
    {
        $processor = new FontProcessor();
        $size = $processor->boxSize('test', new Font('5'));
        $this->assertInstanceOf(SizeInterface::class, $size);
        $this->assertEquals(36, $size->width());
        $this->assertEquals(16, $size->height());
    }

    public function testNativeFontSize(): void
    {
        $processor = new FontProcessor();
        $size = $processor->nativeFontSize(new Font('5'));
        $this->assertEquals(9.12, $size);
    }

    public function testTextBlock(): void
    {
        $processor = new FontProcessor();
        $result = $processor->textBlock('test', new Font(), new Point(0, 0));
        $this->assertInstanceOf(TextBlock::class, $result);
    }

    public function testTypographicalSize(): void
    {
        $processor = new FontProcessor();
        $result = $processor->typographicalSize(new Font());
        $this->assertEquals(8, $result);
    }

    public function testCapHeight(): void
    {
        $processor = new FontProcessor();
        $result = $processor->capHeight(new Font());
        $this->assertEquals(8, $result);
    }

    public function testLeading(): void
    {
        $processor = new FontProcessor();
        $result = $processor->leading(new Font());
        $this->assertEquals(8, $result);
    }
}
