<?php

namespace Intervention\Image\Tests;

use Intervention\Image\Collection;
use Intervention\Image\Drivers\Abstract\AbstractFrame;
use Intervention\Image\Drivers\Abstract\AbstractImage;
use Intervention\Image\EncodedImage;
use Intervention\Image\Geometry\Size;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Mockery;

class AbstractImageTest extends TestCase
{
    protected function abstractImageMock(): AbstractImage
    {
        $frame1 = Mockery::mock(FrameInterface::class);
        $frame1->shouldReceive('ident')->andReturn(1);
        $frame2 = Mockery::mock(FrameInterface::class);
        $frame2->shouldReceive('ident')->andReturn(2);
        $frame3 = Mockery::mock(FrameInterface::class);
        $frame3->shouldReceive('ident')->andReturn(3);

        $collection = new Collection([$frame1, $frame2, $frame3]);

        $mock = Mockery::mock(AbstractImage::class, ImageInterface::class, [$collection])
                ->shouldAllowMockingProtectedMethods()
                ->makePartial();

        $mock->shouldReceive('getWidth')->andReturn(300);
        $mock->shouldReceive('getHeight')->andReturn(200);

        return $mock;
    }

    public function testGetIterator(): void
    {
        $this->assertInstanceOf(Collection::class, $this->abstractImageMock()->getIterator());
    }

    public function testGetFrames(): void
    {
        $this->assertInstanceOf(Collection::class, $this->abstractImageMock()->getFrames());
    }

    public function testGetFrame(): void
    {
        $img = $this->abstractImageMock();

        $this->assertInstanceOf(FrameInterface::class, $img->getFrame());
        $this->assertEquals(1, $img->getFrame()->ident());

        $this->assertInstanceOf(FrameInterface::class, $img->getFrame(1));
        $this->assertEquals(2, $img->getFrame(1)->ident());

        $this->assertInstanceOf(FrameInterface::class, $img->getFrame(2));
        $this->assertEquals(3, $img->getFrame(2)->ident());
    }

    public function testAddFrame(): void
    {
        $img = $this->abstractImageMock();
        $this->assertEquals(3, $img->getFrames()->count());
        $result = $img->addFrame(Mockery::mock(FrameInterface::class));
        $this->assertInstanceOf(AbstractImage::class, $result);
        $this->assertEquals(4, $img->getFrames()->count());
    }

    public function testSetGetLoops(): void
    {
        $img = $this->abstractImageMock();
        $this->assertEquals(0, $img->getLoops());
        $result = $img->setLoops(10);
        $this->assertEquals(10, $img->getLoops());
        $this->assertInstanceOf(AbstractImage::class, $result);
    }

    public function testGetSize(): void
    {
        $img = $this->abstractImageMock();
        $this->assertInstanceOf(Size::class, $img->getSize());
        $this->assertEquals(300, $img->getSize()->getWidth());
        $this->assertEquals(200, $img->getSize()->getHeight());
    }

    public function testIsAnimated(): void
    {
        $img = Mockery::mock(AbstractImage::class, [new Collection()])->makePartial();
        $this->assertFalse($img->isAnimated());

        $collection = new Collection([
            Mockery::mock(FrameInterface::class),
            Mockery::mock(FrameInterface::class),
        ]);
        $img = Mockery::mock(AbstractImage::class, [$collection])->makePartial();
        $this->assertTrue($img->isAnimated());
    }

    public function testModify(): void
    {
        $img = $this->abstractImageMock();

        $modifier = Mockery::mock(ModifierInterface::class);
        $modifier->shouldReceive('apply')->with($img)->andReturn($img);
        $result = $img->modify($modifier);
        $this->assertInstanceOf(ImageInterface::class, $img);
    }

    public function testEncode(): void
    {
        $img = $this->abstractImageMock();

        $encoder = Mockery::mock(EncoderInterface::class);
        $encoded = Mockery::mock(EncodedImage::class);
        $encoder->shouldReceive('encode')->with($img)->andReturn($encoded);
        $result = $img->encode($encoder);
        $this->assertInstanceOf(ImageInterface::class, $img);
    }

    public function testToJpeg(): void
    {
        $img = $this->abstractImageMock();

        $encoded = Mockery::mock(EncodedImage::class);
        $encoder = Mockery::mock(EncoderInterface::class);
        $encoder->shouldReceive('encode')->with($img)->andReturn($encoded);

        $img->shouldReceive('resolveDriverClass')
                ->with('Encoders\JpegEncoder', 45)
                ->andReturn($encoder);

        $result = $img->toJpeg(45);
        $this->assertInstanceOf(EncodedImage::class, $result);
    }

    public function testToGif(): void
    {
        $img = $this->abstractImageMock();

        $encoded = Mockery::mock(EncodedImage::class);
        $encoder = Mockery::mock(EncoderInterface::class);
        $encoder->shouldReceive('encode')->with($img)->andReturn($encoded);

        $img->shouldReceive('resolveDriverClass')
                ->with('Encoders\GifEncoder')
                ->andReturn($encoder);

        $result = $img->toGif();
        $this->assertInstanceOf(EncodedImage::class, $result);
    }

    public function testToPng(): void
    {
        $img = $this->abstractImageMock();

        $encoded = Mockery::mock(EncodedImage::class);
        $encoder = Mockery::mock(EncoderInterface::class);
        $encoder->shouldReceive('encode')->with($img)->andReturn($encoded);

        $img->shouldReceive('resolveDriverClass')
                ->with('Encoders\PngEncoder')
                ->andReturn($encoder);

        $result = $img->toPng();
        $this->assertInstanceOf(EncodedImage::class, $result);
    }

    public function testGreyscale(): void
    {
        $img = $this->abstractImageMock();

        $modifier = Mockery::mock(ModifierInterface::class);
        $modifier->shouldReceive('apply')->with($img)->andReturn($img);

        $img->shouldReceive('resolveDriverClass')
                ->with('Modifiers\GreyscaleModifier')
                ->andReturn($modifier);

        $result = $img->greyscale();
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testBlur(): void
    {
        $img = $this->abstractImageMock();

        $modifier = Mockery::mock(ModifierInterface::class);
        $modifier->shouldReceive('apply')->with($img)->andReturn($img);

        $img->shouldReceive('resolveDriverClass')
                ->with('Modifiers\BlurModifier', 3)
                ->andReturn($modifier);

        $result = $img->blur(3);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testRotate(): void
    {
        $img = $this->abstractImageMock();

        $modifier = Mockery::mock(ModifierInterface::class);
        $modifier->shouldReceive('apply')->with($img)->andReturn($img);

        $img->shouldReceive('resolveDriverClass')
                ->with('Modifiers\RotateModifier', 3, 'cccccc')
                ->andReturn($modifier);

        $result = $img->rotate(3, 'cccccc');
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testPlace(): void
    {
        $img = $this->abstractImageMock();

        $modifier = Mockery::mock(ModifierInterface::class);
        $modifier->shouldReceive('apply')->with($img)->andReturn($img);

        $img->shouldReceive('resolveDriverClass')
                ->with('Modifiers\PlaceModifier', 'el', 'top-left', 0, 0)
                ->andReturn($modifier);

        $result = $img->place('el', 'top-left', 0, 0);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testPickColors(): void
    {
        $color = Mockery::mock(ColorInterface::class);
        $img = $this->abstractImageMock();
        $img->shouldReceive('pickColor')->times(3)->andReturn($color);
        $result = $img->pickColors(1, 2);
        $this->assertInstanceOf(Collection::class, $result);
    }
}
