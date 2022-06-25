<?php

namespace Intervention\Image\Tests\Typography;

use Intervention\Image\Tests\TestCase;
use Intervention\Image\Typography\TextBlock;

class TextBlockTest extends TestCase
{
    protected function getTestBlock(): TextBlock
    {
        return new TextBlock(<<<EOF
            foo
            bar
            baz
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
}
