<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Typography;

use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Typography\TextBlock;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(TextBlock::class)]
final class TextBlockTest extends BaseTestCase
{
    protected TextBlock $block;

    protected function setUp(): void
    {
        $this->block = new TextBlock(<<<EOF
            foo
            FooBar
            bar
            EOF);
    }

    public function testCount(): void
    {
        $this->assertEquals(3, $this->block->count());
    }

    public function testLines(): void
    {
        $this->assertCount(3, $this->block->lines());
    }

    public function testGetLine(): void
    {
        $this->assertEquals('foo', $this->block->line(0));
        $this->assertEquals('FooBar', $this->block->line(1));
        $this->assertEquals('bar', $this->block->line(2));
        $this->assertNull($this->block->line(20));
    }

    public function testLongestLine(): void
    {
        $result = $this->block->longestLine();
        $this->assertEquals('FooBar', (string) $result);
    }
}
