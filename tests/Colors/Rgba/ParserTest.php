<?php

namespace Intervention\Image\Tests\Colors\Rgba;

use Intervention\Image\Colors\Rgba\Color;
use Intervention\Image\Colors\Rgba\Parser;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Colors\Rgba\Parser
 */
class ParserTest extends TestCase
{
    public function testFromHex(): void
    {
        $color = Parser::fromHex('ccc');
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals([204, 204, 204, 255], $color->toArray());

        $color = Parser::fromHex('cccccc');
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals([204, 204, 204, 255], $color->toArray());

        $color = Parser::fromHex('#cccccc');
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals([204, 204, 204, 255], $color->toArray());

        $color = Parser::fromHex('#cccccccc');
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals([204, 204, 204, 204], $color->toArray());

        $color = Parser::fromHex('#cccc');
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals([204, 204, 204, 204], $color->toArray());

        $color = Parser::fromHex('cccccccc');
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals([204, 204, 204, 204], $color->toArray());

        $color = Parser::fromHex('cccc');
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals([204, 204, 204, 204], $color->toArray());
    }

    public function testFromString(): void
    {
        $color = Parser::fromString('rgba(204, 204, 204, 1)');
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals([204, 204, 204, 255], $color->toArray());

        $color = Parser::fromString('rgba(204,204,204,1.0)');
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals([204, 204, 204, 255], $color->toArray());

        $color = Parser::fromString('rgba(204,204,204,0.2)');
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals([204, 204, 204, 51], $color->toArray());

        $color = Parser::fromString('rgba(204,204, 204, .2)');
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals([204, 204, 204, 51], $color->toArray());

        $this->expectException(ColorException::class);
        $color = Parser::fromString('rgba(204, 204, 204, 1.2)');
    }
}
