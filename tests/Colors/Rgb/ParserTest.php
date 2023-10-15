<?php

namespace Intervention\Image\Tests\Colors\Rgb;

use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Colors\Rgb\Parser;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Colors\Rgb\Parser
 */
class ParserTest extends TestCase
{
    public function testFromHex(): void
    {
        $color = Parser::fromHex('ccc');
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals([204, 204, 204], $color->toArray());

        $color = Parser::fromHex('cccccc');
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals([204, 204, 204], $color->toArray());

        $color = Parser::fromHex('#cccccc');
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals([204, 204, 204], $color->toArray());

        $this->expectException(ColorException::class);
        (new Parser())->fromHex('cccccccc');
    }

    public function testFromString(): void
    {
        $color = Parser::fromString('rgb(204, 204, 204)');
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals([204, 204, 204], $color->toArray());

        $color = Parser::fromString('rgb(204,204,204)');
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals([204, 204, 204], $color->toArray());

        $this->expectException(ColorException::class);
        (new Parser())->fromString('rgb(204,204,204,1)');

        $this->expectException(ColorException::class);
        (new Parser())->fromString('rgb(120)');

        $this->expectException(ColorException::class);
        (new Parser())->fromString('rgba(204,204,204,1)');
    }

    public function testFromName(): void
    {
        $color = Parser::fromName('salmon');
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals('fa8072', $color->toHex());
    }
}
