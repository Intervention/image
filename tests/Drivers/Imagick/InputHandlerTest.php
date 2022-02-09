<?php

namespace Intervention\Image\Tests\Drivers\Imagick;

use Intervention\Image\Drivers\Imagick\Color;
use Intervention\Image\Drivers\Imagick\Image;
use Intervention\Image\Drivers\Imagick\InputHandler;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Tests\TestCase;

/**
 * @requires extension imagick
 * @covers \Intervention\Image\Drivers\Imagick\InputHandler
 */
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
    }
}
