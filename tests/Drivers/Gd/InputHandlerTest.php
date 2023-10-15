<?php

namespace Intervention\Image\Tests\Drivers\Gd;

use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Rgba\Color as RgbaColor;
use Intervention\Image\Drivers\Gd\Image;
use Intervention\Image\Drivers\Gd\InputHandler;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension gd
 * @covers \Intervention\Image\Drivers\Gd\InputHandler
 */
class GdInputHandlerTest extends TestCase
{
    public function testHandleEmptyString(): void
    {
        $handler = new InputHandler();
        $this->expectException(DecoderException::class);
        $handler->handle('');
    }

    public function testHandleBinaryImage(): void
    {
        $handler = new InputHandler();
        $input = file_get_contents(__DIR__ . '/../../images/animation.gif');
        $result = $handler->handle($input);
        $this->assertInstanceOf(Image::class, $result);
    }

    public function testHandleFilePathImage(): void
    {
        $handler = new InputHandler();
        $input = __DIR__ . '/../../images/animation.gif';
        $result = $handler->handle($input);
        $this->assertInstanceOf(Image::class, $result);
    }

    public function testHandleBase64Image(): void
    {
        $handler = new InputHandler();
        $input = base64_encode(file_get_contents(__DIR__ . '/../../images/animation.gif'));
        $result = $handler->handle($input);
        $this->assertInstanceOf(Image::class, $result);
    }

    public function testHandleDataUriImage(): void
    {
        $handler = new InputHandler();
        $input = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAFCAYAAACNbyblAAAAHElEQVQI12P4//8/w38GIAXDIBKE0DHxgljNBAAO9TXL0Y4OHwAAAABJRU5ErkJggg==';
        $result = $handler->handle($input);
        $this->assertInstanceOf(Image::class, $result);
    }

    public function testHandleHexColor(): void
    {
        $handler = new InputHandler();
        $input = 'ccff33';
        $result = $handler->handle($input);
        $this->assertInstanceOf(RgbColor::class, $result);
        $this->assertEquals([204, 255, 51], $result->toArray());

        $handler = new InputHandler();
        $input = 'cf3';
        $result = $handler->handle($input);
        $this->assertInstanceOf(RgbColor::class, $result);
        $this->assertEquals([204, 255, 51], $result->toArray());

        $handler = new InputHandler();
        $input = '#123456';
        $result = $handler->handle($input);
        $this->assertInstanceOf(RgbColor::class, $result);
        $this->assertEquals([18, 52, 86], $result->toArray());

        $handler = new InputHandler();
        $input = '#333';
        $result = $handler->handle($input);
        $this->assertInstanceOf(RgbColor::class, $result);
        $this->assertEquals([51, 51, 51], $result->toArray());

        $handler = new InputHandler();
        $input = '#3333';
        $result = $handler->handle($input);
        $this->assertInstanceOf(RgbaColor::class, $result);
        $this->assertEquals([51, 51, 51, 51], $result->toArray());

        $handler = new InputHandler();
        $input = '#33333333';
        $result = $handler->handle($input);
        $this->assertInstanceOf(RgbaColor::class, $result);
        $this->assertEquals([51, 51, 51, 51], $result->toArray());
    }

    public function testHandleRgbString(): void
    {
        $handler = new InputHandler();
        $result = $handler->handle('rgb(10, 20, 30)');
        $this->assertInstanceOf(RgbColor::class, $result);
        $this->assertEquals([10, 20, 30], $result->toArray());

        $handler = new InputHandler();
        $result = $handler->handle('rgba(10, 20, 30, 1.0)');
        $this->assertInstanceOf(RgbaColor::class, $result);
        $this->assertEquals([10, 20, 30, 255], $result->toArray());
    }

    // public function testHandleTransparent(): void
    // {
    //     $handler = new InputHandler();
    //     $input = 'transparent';
    //     $result = $handler->handle($input);
    //     $this->assertInstanceOf(Color::class, $result);
    // }
}
