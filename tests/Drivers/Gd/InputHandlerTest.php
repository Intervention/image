<?php

namespace Intervention\Image\Tests\Drivers\Gd;

use Intervention\Image\Collection;
use Intervention\Image\Drivers\Gd\Color;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Drivers\Gd\Image;
use Intervention\Image\Drivers\Gd\InputHandler;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Geometry\Size;
use Intervention\Image\Tests\TestCase;

class InputHandlerTest extends TestCase
{
    public function testHandleEmptyString(): void
    {
        $handler = new InputHandler();
        $this->expectException(DecoderException::class);
        $result = $handler->handle('');
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

    public function testHandleArrayColor(): void
    {
        $handler = new InputHandler();
        $input = [181, 55, 23, .5];
        $result = $handler->handle($input);
        $this->assertInstanceOf(Color::class, $result);

        $handler = new InputHandler();
        $input = [181, 55, 23];
        $result = $handler->handle($input);
        $this->assertInstanceOf(Color::class, $result);
    }

    public function testHandleHexColor(): void
    {
        $handler = new InputHandler();
        $input = 'ccff33';
        $result = $handler->handle($input);
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals(204, $result->red());
        $this->assertEquals(255, $result->green());
        $this->assertEquals(51, $result->blue());

        $handler = new InputHandler();
        $input = 'cf3';
        $result = $handler->handle($input);
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals(204, $result->red());
        $this->assertEquals(255, $result->green());
        $this->assertEquals(51, $result->blue());

        $handler = new InputHandler();
        $input = '#123456';
        $result = $handler->handle($input);
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals(18, $result->red());
        $this->assertEquals(52, $result->green());
        $this->assertEquals(86, $result->blue());

        $handler = new InputHandler();
        $input = '#333';
        $result = $handler->handle($input);
        $this->assertInstanceOf(Color::class, $result);
        $this->assertEquals(51, $result->red());
        $this->assertEquals(51, $result->green());
        $this->assertEquals(51, $result->blue());
    }

    public function testHandleTransparent(): void
    {
        $handler = new InputHandler();
        $input = 'transparent';
        $result = $handler->handle($input);
        $this->assertInstanceOf(Color::class, $result);
    }
}
