<?php

namespace Intervention\Image\Tests\Typography;

use Intervention\Image\Geometry\Point;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Typography\TextBlock;

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
}
