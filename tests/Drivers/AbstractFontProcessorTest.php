<?php

namespace Intervention\Image\Tests\Drivers;

use Intervention\Image\Drivers\AbstractFontProcessor;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\FontInterface;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Typography\Font;
use Intervention\Image\Typography\TextBlock;
use Mockery;

class AbstractFontProcessorTest extends TestCase
{
    private function getMock(FontInterface $font)
    {
        // create mock
        $mock = Mockery::mock(AbstractFontProcessor::class, [$font])
                ->shouldAllowMockingProtectedMethods()
                ->makePartial();

        $mock->shouldReceive('boxSize')->with('Hy')->andReturn(new Rectangle(123, 456));
        $mock->shouldReceive('boxSize')->with('T')->andReturn(new Rectangle(12, 34));
        $mock->shouldReceive('boxSize')->with('foobar')->andReturn(new Rectangle(4, 8));

        return $mock;
    }

    public function testLeadingInPixels(): void
    {
        $mock = $this->getMock((new Font())->setLineHeight(2));
        $this->assertEquals(912, $mock->leadingInPixels());
    }

    public function testCapHeight(): void
    {
        $mock = $this->getMock((new Font())->setLineHeight(2));
        $this->assertEquals(34, $mock->capHeight());
    }

    public function testFontSizeInPixels(): void
    {
        $mock = $this->getMock((new Font())->setLineHeight(2));
        $this->assertEquals(456, $mock->fontSizeInPixels());
    }

    public function testAlignedTextBlock(): void
    {
        $mock = $this->getMock((new Font())->setLineHeight(2));
        $block = $mock->alignedTextBlock(new Point(0, 0), 'foobar');
        $this->assertInstanceOf(TextBlock::class, $block);
    }

    public function testBoundingBox(): void
    {
        $mock = $this->getMock((new Font())->setLineHeight(2));
        $box = $mock->boundingBox(new TextBlock('foobar'));
        $this->assertInstanceOf(Polygon::class, $box);
        $this->assertEquals(4, $box->width());
        $this->assertEquals(34, $box->height());
    }
}
