<?php

namespace Intervention\Image\Tests\Typography;

use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Interfaces\FontInterface;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Typography\TextBlock;
use Mockery;

class TextBlockTest extends TestCase
{
    protected function getTestBlock(): TextBlock
    {
        return new TextBlock(<<<EOF
            foo
            FooBar
            bar
            EOF);
    }

    public function testConstructor(): void
    {
        $block = $this->getTestBlock();
        $this->assertInstanceOf(TextBlock::class, $block);
        $this->assertEquals(3, $block->count());
    }

    public function testLines(): void
    {
        $block = $this->getTestBlock();
        $this->assertCount(3, $block->lines());
    }

    public function testGetLine(): void
    {
        $block = $this->getTestBlock();
        $this->assertEquals('foo', $block->getLine(0));
        $this->assertEquals('FooBar', $block->getLine(1));
        $this->assertEquals('bar', $block->getLine(2));
    }

    public function testLongestLine(): void
    {
        $block = $this->getTestBlock();
        $result = $block->longestLine();
        $this->assertEquals('FooBar', (string) $result);
    }

    public function testGetBoundingBox(): void
    {
        $block = $this->getTestBlock();
        $font = Mockery::mock(FontInterface::class)
                ->shouldAllowMockingProtectedMethods()
                ->makePartial();

        $font->shouldReceive('getBoxSize')->andReturn(
            new Polygon([
                new Point(0, 0),
                new Point(300, 0),
                new Point(300, 150),
                new Point(0, 150),
            ])
        );

        $font->shouldReceive('leadingInPixels')->andReturn(30);
        $font->shouldReceive('getAlign')->andReturn('left');
        $font->shouldReceive('getValign')->andReturn('bottom');
        $font->shouldReceive('getAngle')->andReturn(0);
        $font->shouldReceive('capHeight')->andReturn(22);

        $box = $block->getBoundingBox($font, new Point(10, 15));
        $this->assertEquals(300, $box->getWidth());
        $this->assertEquals(82, $box->getHeight());
        $this->assertEquals(10, $box->getPivot()->getX());
        $this->assertEquals(15, $box->getPivot()->getY());
    }
}
