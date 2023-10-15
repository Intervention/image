<?php

namespace Intervention\Image\Tests\Colors\Cmyk;

use Intervention\Image\Colors\Cmyk\Color;
use Intervention\Image\Colors\Cmyk\Parser;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Colors\Cmyk\Parser
 */
class ParserTest extends TestCase
{
    public function testParse(): void
    {
        $color = Parser::fromString('cmyk(100, 0, 0, 0)');
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals([100, 0, 0, 0], $color->toArray());
    }

    public function testFromString(): void
    {
        $color = Parser::fromString('cmyk(100, 0, 0, 0)');
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals([100, 0, 0, 0], $color->toArray());

        $color = Parser::fromString('cmyk(100%, 0%, 0%, 0%)');
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals([100, 0, 0, 0], $color->toArray());
    }
}
