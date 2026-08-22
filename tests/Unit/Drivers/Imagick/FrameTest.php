<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Imagick;
use ImagickPixel;
use Intervention\Image\Drivers\Imagick\Core;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Drivers\Imagick\Frame;
use Intervention\Image\Image;
use Intervention\Image\Size;
use Intervention\Image\Tests\BaseTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(Frame::class)]
final class FrameTest extends BaseTestCase
{
    protected function getTestFrame(): Frame
    {
        $imagick = new Imagick();
        $imagick->newImage(3, 2, new ImagickPixel('red'), 'png');
        $imagick->setImageDelay(125); // 1.25 seconds
        $imagick->setImageDispose(0);
        $imagick->setImagePage(3, 2, 8, 9);

        return new Frame($imagick);
    }

    /**
     * Create a two frame animation, the first frame red and the second blue.
     */
    protected function getTestAnimation(): Imagick
    {
        $imagick = new Imagick();
        $imagick->setFormat('gif');

        foreach (['red', 'blue'] as $color) {
            $frame = new Imagick();
            $frame->newImage(3, 2, new ImagickPixel($color), 'gif');
            $frame->setImageDelay(10);
            $imagick->addImage($frame);
        }

        return $imagick;
    }

    public function testConstructor(): void
    {
        $frame = $this->getTestFrame();
        $this->assertInstanceOf(Frame::class, $frame);
    }

    public function testGetSize(): void
    {
        $frame = $this->getTestFrame();
        $this->assertInstanceOf(Size::class, $frame->size());
    }

    public function testSetGetDelay(): void
    {
        $frame = $this->getTestFrame();
        $this->assertEquals(1.25, $frame->delay());

        $result = $frame->setDelay(2.5);
        $this->assertInstanceOf(Frame::class, $result);
        $this->assertEquals(2.5, $frame->delay());
        $this->assertEquals(250, $frame->native()->getImageDelay());
    }

    public function testSetGetDisposalMethod(): void
    {
        $frame = $this->getTestFrame();
        $this->assertEquals(0, $frame->disposalMethod());

        $result = $frame->setDisposalMethod(3);
        $this->assertInstanceOf(Frame::class, $result);
        $this->assertEquals(3, $frame->disposalMethod());
    }

    public function testSetGetOffsetLeft(): void
    {
        $frame = $this->getTestFrame();
        $this->assertEquals(8, $frame->offsetLeft());

        $result = $frame->setOffsetLeft(100);
        $this->assertInstanceOf(Frame::class, $result);
        $this->assertEquals(100, $frame->offsetLeft());
    }

    public function testSetGetOffsetTop(): void
    {
        $frame = $this->getTestFrame();
        $this->assertEquals(9, $frame->offsetTop());

        $result = $frame->setOffsetTop(100);
        $this->assertInstanceOf(Frame::class, $result);
        $this->assertEquals(100, $frame->offsetTop());
    }

    public function testSetGetOffset(): void
    {
        $frame = $this->getTestFrame();
        $this->assertEquals(8, $frame->offsetLeft());
        $this->assertEquals(9, $frame->offsetTop());

        $result = $frame->setOffset(100, 200);
        $this->assertInstanceOf(Frame::class, $result);
        $this->assertEquals(100, $frame->offsetLeft());
        $this->assertEquals(200, $frame->offsetTop());
    }

    public function testToImage(): void
    {
        $frame = $this->getTestFrame();
        $this->assertInstanceOf(Image::class, $frame->toImage(new Driver()));
    }

    public function testToImageOfAnimationFrame(): void
    {
        $core = new Core($this->getTestAnimation());

        foreach (['ff0000', '0000ff'] as $position => $hex) {
            $image = $core->frame($position)->toImage(new Driver());
            $this->assertEquals(1, $image->count());
            $this->assertEquals($hex, $image->colorAt(0, 0)->toHex());
        }
    }

    public function testToImageOfAnimationFrameIsDetached(): void
    {
        $core = new Core($this->getTestAnimation());
        $image = $core->frame(0)->toImage(new Driver());

        // the resulting image must not share the sequence of the core, which
        // still holds all frames and gets seeked around by every frame access
        $this->assertNotSame($core->native(), $image->core()->native());
    }

    public function testSetGetNative(): void
    {
        $frame = $this->getTestFrame();
        $this->assertInstanceOf(Imagick::class, $frame->native());

        $imagick = new Imagick();
        $imagick->newImage(5, 5, new ImagickPixel('blue'), 'png');
        $result = $frame->setNative($imagick);
        $this->assertInstanceOf(Frame::class, $result);
        $this->assertSame($imagick, $frame->native());
    }

    public function testDebugInfo(): void
    {
        $frame = $this->getTestFrame();
        $info = $frame->__debugInfo();
        $this->assertIsArray($info);
        $this->assertArrayHasKey('delay', $info);
        $this->assertArrayHasKey('left', $info);
        $this->assertArrayHasKey('top', $info);
        $this->assertArrayHasKey('disposalMethod', $info);
    }
}
