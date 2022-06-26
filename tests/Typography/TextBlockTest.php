<?php

namespace Intervention\Image\Tests\Typography;

use Intervention\Image\Tests\TestCase;
use Intervention\Image\Typography\TextBlock;
use Intervention\Image\Drivers\Abstract\AbstractFont;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Polygon;
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

    public function testAlignByFont(): void
    {
        $font = Mockery::mock(AbstractFont::class)
                ->shouldAllowMockingProtectedMethods()
                ->makePartial();

        $font->shouldReceive('getBoxSize')->andReturn(
            new Polygon([
                new Point(-1, -29),
                new Point(141, -29),
                new Point(141, 98),
                new Point(-1, 98),
            ])
        );

        // $font->shouldReceive('capHeight')->andReturn(22);

        $font->shouldReceive('leadingInPixels')->andReturn(74);
        $font->angle(45);

        $block = $this->getTestBlock(); // before
        $this->assertEquals(0, $block->getLine(0)->getPosition()->getX());
        $this->assertEquals(0, $block->getLine(0)->getPosition()->getY());
        $this->assertEquals(0, $block->getLine(1)->getPosition()->getX());
        $this->assertEquals(0, $block->getLine(1)->getPosition()->getY());
        $this->assertEquals(0, $block->getLine(2)->getPosition()->getX());
        $this->assertEquals(0, $block->getLine(2)->getPosition()->getY());

        $result = $block->alignByFont($font); // after
        $this->assertInstanceOf(TextBlock::class, $result);
        $this->assertEquals(0, $block->getLine(0)->getPosition()->getX());
        $this->assertEquals(0, $block->getLine(0)->getPosition()->getY());
        $this->assertEquals(-52, $block->getLine(1)->getPosition()->getX());
        $this->assertEquals(52, $block->getLine(1)->getPosition()->getY());
        $this->assertEquals(-104, $block->getLine(2)->getPosition()->getX());
        $this->assertEquals(104, $block->getLine(2)->getPosition()->getY());
    }
}
