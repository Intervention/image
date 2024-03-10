<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers;

use Intervention\Image\Drivers\AbstractFontProcessor;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Typography\Font;
use Intervention\Image\Typography\TextBlock;
use Mockery;
use PHPUnit\Metadata\CoversClass;

#[CoversClass(AbstractFontProcessor::class)]
class AbstractFontProcessorTest extends BaseTestCase
{
    public function testTextBlock(): void
    {
        $text = 'AAAA BBBB CCCC';
        $font = (new Font($this->getTestResourcePath('test.ttf')))->setWrapWidth(20)->setSize(50);
        $processor = Mockery::mock(AbstractFontProcessor::class)->makePartial();
        $processor->shouldReceive('boxSize')->with('T', $font)->andReturn(new Rectangle(12, 6));
        $processor->shouldReceive('boxSize')->with('Hy', $font)->andReturn(new Rectangle(24, 6));
        $processor->shouldReceive('boxSize')->with('AAAA', $font)->andReturn(new Rectangle(24, 6));
        $processor->shouldReceive('boxSize')->with('AAAA BBBB', $font)->andReturn(new Rectangle(24, 6));
        $processor->shouldReceive('boxSize')->with('BBBB', $font)->andReturn(new Rectangle(24, 6));
        $processor->shouldReceive('boxSize')->with('BBBB CCCC', $font)->andReturn(new Rectangle(24, 6));
        $processor->shouldReceive('boxSize')->with('CCCC', $font)->andReturn(new Rectangle(24, 6));
        $processor->shouldReceive('boxSize')->with($text, $font)->andReturn(new Rectangle(100, 25));
        $block = $processor->textBlock($text, $font, new Point(0, 0));
        $this->assertInstanceOf(TextBlock::class, $block);
        $this->assertEquals(3, $block->count());
    }

    public function testNativeFontSize(): void
    {
        $font = (new Font($this->getTestResourcePath('test.ttf')))->setSize(32);
        $processor = Mockery::mock(AbstractFontProcessor::class)->makePartial();
        $this->assertEquals(32, $processor->nativeFontSize($font));
    }

    public function testTypographicalSize(): void
    {
        $font = new Font($this->getTestResourcePath('test.ttf'));
        $processor = Mockery::mock(AbstractFontProcessor::class)->makePartial();
        $processor->shouldReceive('boxSize')->with('Hy', $font)->andReturn(new Rectangle(24, 6));
        $this->assertEquals(6, $processor->typographicalSize($font));
    }

    public function testCapHeight(): void
    {
        $font = new Font($this->getTestResourcePath('test.ttf'));
        $processor = Mockery::mock(AbstractFontProcessor::class)->makePartial();
        $processor->shouldReceive('boxSize')->with('T', $font)->andReturn(new Rectangle(24, 6));
        $this->assertEquals(6, $processor->capHeight($font));
    }

    public function testLeading(): void
    {
        $font = (new Font($this->getTestResourcePath('test.ttf')))->setLineHeight(3);
        $processor = Mockery::mock(AbstractFontProcessor::class)->makePartial();
        $processor->shouldReceive('boxSize')->with('Hy', $font)->andReturn(new Rectangle(24, 6));
        $this->assertEquals(18, $processor->leading($font));
    }
}
