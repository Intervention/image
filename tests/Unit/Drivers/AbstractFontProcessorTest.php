<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers;

use Intervention\Image\Alignment;
use Intervention\Image\Drivers\AbstractFontProcessor;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Size;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Resource;
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
        $font = (new Font(Resource::create('test.ttf')->path()))
            ->setWrapWidth(20)
            ->setSize(50)
            ->setLineHeight(1.25)
            ->setAlignment(Alignment::CENTER);

        $processor = Mockery::mock(AbstractFontProcessor::class)->makePartial();

        $processor
            ->shouldReceive('boxSize')
            ->with('T', $font)
            ->andReturn(new Size(12, 6));
        $processor
            ->shouldReceive('boxSize')
            ->with('Hy', $font)
            ->andReturn(new Size(24, 6));

        $processor
            ->shouldReceive('boxSize')
            ->with('AAAA', $font)
            ->andReturn(new Size(24, 6, new Point(1000, 0)));

        $processor
            ->shouldReceive('boxSize')
            ->with('AAAA BBBB', $font)
            ->andReturn(new Size(24, 6));

        $processor
            ->shouldReceive('boxSize')
            ->with('BBBB', $font)
            ->andReturn(new Size(24, 6, new Point(2000, 0)));

        $processor
            ->shouldReceive('boxSize')
            ->with('BBBB CCCC', $font)
            ->andReturn(new Size(24, 6));

        $processor
            ->shouldReceive('boxSize')
            ->with('CCCC', $font)
            ->andReturn(new Size(24, 6, new Point(3000, 0)));

        $processor
            ->shouldReceive('boxSize')
            ->with($text, $font)
            ->andReturn(new Size(100, 25, new Point(10, 0)));

        $block = $processor->textBlock($text, $font, new Point(0, 0));

        $this->assertInstanceOf(TextBlock::class, $block);
        $this->assertEquals(3, $block->count());
        $this->assertEquals(-512, $block->getAtPosition(0)->position()->x());
        $this->assertEquals(-16, $block->getAtPosition(0)->position()->y());
        $this->assertEquals(-1012, $block->getAtPosition(1)->position()->x());
        $this->assertEquals(-8, $block->getAtPosition(1)->position()->y());
        $this->assertEquals(-1512, $block->getAtPosition(2)->position()->x());
        $this->assertEquals(0, $block->getAtPosition(2)->position()->y());
    }

    public function testNativeFontSize(): void
    {
        $font = (new Font(Resource::create('test.ttf')->path()))->setSize(32);
        $processor = Mockery::mock(AbstractFontProcessor::class)->makePartial();
        $this->assertEquals(32, $processor->nativeFontSize($font));
    }

    public function testTypographicalSize(): void
    {
        $font = new Font(Resource::create('test.ttf')->path());
        $processor = Mockery::mock(AbstractFontProcessor::class)->makePartial();
        $processor->shouldReceive('boxSize')->with('Hy', $font)->andReturn(new Size(24, 6));
        $this->assertEquals(6, $processor->typographicalSize($font));
    }

    public function testCapHeight(): void
    {
        $font = new Font(Resource::create('test.ttf')->path());
        $processor = Mockery::mock(AbstractFontProcessor::class)->makePartial();
        $processor->shouldReceive('boxSize')->with('T', $font)->andReturn(new Size(24, 6));
        $this->assertEquals(6, $processor->capHeight($font));
    }

    public function testLeading(): void
    {
        $font = (new Font(Resource::create('test.ttf')->path()))->setLineHeight(3);
        $processor = Mockery::mock(AbstractFontProcessor::class)->makePartial();
        $processor->shouldReceive('boxSize')->with('Hy', $font)->andReturn(new Size(24, 6));
        $this->assertEquals(18, $processor->leading($font));
    }
}
