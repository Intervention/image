<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd;

use Intervention\Image\Drivers\Gd\FontProcessor;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Typography\Font;
use Intervention\Image\Typography\TextBlock;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('gd')]
#[CoversClass(FontProcessor::class)]
final class FontProcessorTest extends BaseTestCase
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

    public function testBoxSizeTtf(): void
    {
        $processor = new FontProcessor();
        $size = $processor->boxSize('ABC', $this->testFont());
        $this->assertInstanceOf(SizeInterface::class, $size);
        $this->assertContains($size->width(), [74, 75, 76]);
        $this->assertContains($size->height(), [19, 20, 21]);
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

    public function testNativeFontSizeTtf(): void
    {
        $processor = new FontProcessor();
        $size = $processor->nativeFontSize($this->testFont());
        $this->assertEquals(42.56, $size);
    }

    public function testTextBlockTtf(): void
    {
        $processor = new FontProcessor();
        $result = $processor->textBlock('test', $this->testFont(), new Point(0, 0));
        $this->assertInstanceOf(TextBlock::class, $result);
    }

    public function testTypographicalSizeTtf(): void
    {
        $processor = new FontProcessor();
        $result = $processor->typographicalSize($this->testFont());
        $this->assertContains($result, [44, 45]);
    }

    public function testCapHeightTtf(): void
    {
        $processor = new FontProcessor();
        $result = $processor->capHeight($this->testFont());
        $this->assertContains($result, [44, 45]);
    }

    public function testLeadingTtf(): void
    {
        $processor = new FontProcessor();
        $result = $processor->leading($this->testFont());
        $this->assertContains($result, [44, 45]);
    }

    private function testFont(): Font
    {
        return (new Font($this->getTestResourcePath('test.ttf')))->setSize(56);
    }
}
