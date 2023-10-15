<?php

namespace Intervention\Image\Tests\Colors;

use Intervention\Image\Colors\Cmyk\Color as CmykColor;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Rgba\Color as RgbaColor;
use Intervention\Image\Colors\Parser;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Colors\Parser
 */
class ParserTest extends TestCase
{
    public function testParse(): void
    {
        $color = Parser::parse('ccc');
        $this->assertInstanceOf(RgbColor::class, $color);
        $this->assertEquals([204, 204, 204], $color->toArray());

        $color = Parser::parse('rgb(204, 204, 204)');
        $this->assertInstanceOf(RgbColor::class, $color);
        $this->assertEquals([204, 204, 204], $color->toArray());

        $color = Parser::parse('rgba(204, 204, 204, 1)');
        $this->assertInstanceOf(RgbaColor::class, $color);
        $this->assertEquals([204, 204, 204, 255], $color->toArray());

        $color = Parser::parse('cccc');
        $this->assertInstanceOf(RgbColor::class, $color);
        $this->assertEquals([204, 204, 204, 204], $color->toArray());

        $color = Parser::parse('cccccccc');
        $this->assertInstanceOf(RgbColor::class, $color);
        $this->assertEquals([204, 204, 204, 204], $color->toArray());

        $color = Parser::parse('salmon');
        $this->assertInstanceOf(RgbColor::class, $color);
        $this->assertEquals('fa8072', $color->toHex());

        $color = Parser::parse('cmyk(100, 100, 0,0)');
        $this->assertInstanceOf(CmykColor::class, $color);
        $this->assertEquals([100, 100, 0, 0], $color->toArray());
    }
}
