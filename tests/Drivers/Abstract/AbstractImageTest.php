<?php

namespace Intervention\Image\Tests\Drivers\Abstract;

use Intervention\Image\Collection;
use Intervention\Image\Drivers\Abstract\AbstractImage;
use Intervention\Image\EncodedImage;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\PointInterface;
use Intervention\Image\Tests\TestCase;
use Mockery;

/**
 * @covers \Intervention\Image\Drivers\Abstract\AbstractImage
 */
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

        $mock = Mockery::mock(AbstractImage::class, ImageInterface::class)
                ->shouldAllowMockingProtectedMethods()
                ->makePartial();

        $mock->shouldReceive('getWidth')->andReturn(300);
        $mock->shouldReceive('getHeight')->andReturn(200);
        $mock->shouldReceive('getIterator')->andReturn($collection);

        return $mock;
    }

    public function testGetSize(): void
    {
        $img = $this->abstractImageMock();
        $this->assertInstanceOf(Rectangle::class, $img->getSize());
        $this->assertEquals(300, $img->getSize()->getWidth());
        $this->assertEquals(200, $img->getSize()->getHeight());
    }

    public function testSizeAlias(): void
    {
        $img = $this->abstractImageMock();
        $this->assertInstanceOf(Rectangle::class, $img->getSize());
        $this->assertEquals(300, $img->size()->getWidth());
        $this->assertEquals(200, $img->size()->getHeight());
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

    public function testToWebp(): void
    {
        $img = $this->abstractImageMock();

        $encoded = Mockery::mock(EncodedImage::class);
        $encoder = Mockery::mock(EncoderInterface::class);
        $encoder->shouldReceive('encode')->with($img)->andReturn($encoded);

        $img->shouldReceive('resolveDriverClass')
            ->with('Encoders\WebpEncoder', 45)
            ->andReturn($encoder);

        $result = $img->toWebp(45);
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

    public function testInvert(): void
    {
        $img = $this->abstractImageMock();

        $modifier = Mockery::mock(ModifierInterface::class);
        $modifier->shouldReceive('apply')->with($img)->andReturn($img);

        $img->shouldReceive('resolveDriverClass')
            ->with('Modifiers\InvertModifier')
            ->andReturn($modifier);

        $result = $img->invert();
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testBrightness(): void
    {
        $img = $this->abstractImageMock();

        $modifier = Mockery::mock(ModifierInterface::class);
        $modifier->shouldReceive('apply')->with($img)->andReturn($img);

        $img->shouldReceive('resolveDriverClass')
            ->with('Modifiers\BrightnessModifier', 5)
            ->andReturn($modifier);

        $result = $img->brightness(5);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testContrast(): void
    {
        $img = $this->abstractImageMock();

        $modifier = Mockery::mock(ModifierInterface::class);
        $modifier->shouldReceive('apply')->with($img)->andReturn($img);

        $img->shouldReceive('resolveDriverClass')
            ->with('Modifiers\ContrastModifier', 5)
            ->andReturn($modifier);

        $result = $img->contrast(5);
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

    public function testFill(): void
    {
        $img = $this->abstractImageMock();

        $modifier = Mockery::mock(ModifierInterface::class);
        $modifier->shouldReceive('apply')->with($img)->andReturn($img);

        $color = Mockery::mock(ColorInterface::class);

        $img->shouldReceive('handleInput')
            ->with('abcdef')
            ->andReturn($color);
        $img->shouldReceive('resolveDriverClass')
                ->with('Modifiers\FillModifier', $color, null)
                ->andReturn($modifier);

        $result = $img->fill('abcdef');
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testPixelate(): void
    {
        $img = $this->abstractImageMock();

        $modifier = Mockery::mock(ModifierInterface::class);
        $modifier->shouldReceive('apply')->with($img)->andReturn($img);

        $img->shouldReceive('resolveDriverClass')
                ->with('Modifiers\PixelateModifier', 42)
                ->andReturn($modifier);

        $result = $img->pixelate(42);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testSharpen(): void
    {
        $img = $this->abstractImageMock();

        $modifier = Mockery::mock(ModifierInterface::class);
        $modifier->shouldReceive('apply')->with($img)->andReturn($img);

        $img->shouldReceive('resolveDriverClass')
                ->with('Modifiers\SharpenModifier', 7)
                ->andReturn($modifier);

        $result = $img->sharpen(7);
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

    public function testResize(): void
    {
        $img = $this->abstractImageMock();

        $modifier = Mockery::mock(ModifierInterface::class);
        $modifier->shouldReceive('apply')->with($img)->andReturn($img);

        $img->shouldReceive('resolveDriverClass')
            ->with('Modifiers\ResizeModifier', 200, 100)
            ->andReturn($modifier);

        $result = $img->resize(200, 100);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testResizeDown(): void
    {
        $img = $this->abstractImageMock();

        $modifier = Mockery::mock(ModifierInterface::class);
        $modifier->shouldReceive('apply')->with($img)->andReturn($img);

        $img->shouldReceive('resolveDriverClass')
            ->with('Modifiers\ResizeDownModifier', 200, 100)
            ->andReturn($modifier);

        $result = $img->resizeDown(200, 100);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testScale(): void
    {
        $img = $this->abstractImageMock();

        $modifier = Mockery::mock(ModifierInterface::class);
        $modifier->shouldReceive('apply')->with($img)->andReturn($img);

        $img->shouldReceive('resolveDriverClass')
            ->with('Modifiers\ScaleModifier', 200, 100)
            ->andReturn($modifier);

        $result = $img->scale(200, 100);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testScaleDown(): void
    {
        $img = $this->abstractImageMock();

        $modifier = Mockery::mock(ModifierInterface::class);
        $modifier->shouldReceive('apply')->with($img)->andReturn($img);

        $img->shouldReceive('resolveDriverClass')
            ->with('Modifiers\ScaleDownModifier', 200, 100)
            ->andReturn($modifier);

        $result = $img->scaleDown(200, 100);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testFit(): void
    {
        $img = $this->abstractImageMock();

        $modifier = Mockery::mock(ModifierInterface::class);
        $modifier->shouldReceive('apply')->with($img)->andReturn($img);

        $img->shouldReceive('resolveDriverClass')
            ->with('Modifiers\FitModifier', 200, 100, 'center')
            ->andReturn($modifier);

        $result = $img->fit(200, 100, 'center');
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testFitDown(): void
    {
        $img = $this->abstractImageMock();

        $modifier = Mockery::mock(ModifierInterface::class);
        $modifier->shouldReceive('apply')->with($img)->andReturn($img);

        $img->shouldReceive('resolveDriverClass')
            ->with('Modifiers\FitDownModifier', 200, 100, 'center')
            ->andReturn($modifier);

        $result = $img->fitDown(200, 100, 'center');
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testPad(): void
    {
        $img = $this->abstractImageMock();

        $modifier = Mockery::mock(ModifierInterface::class);
        $modifier->shouldReceive('apply')->with($img)->andReturn($img);

        $img->shouldReceive('resolveDriverClass')
            ->with('Modifiers\PadModifier', 200, 100, 'ffffff', 'center')
            ->andReturn($modifier);

        $result = $img->pad(200, 100, 'ffffff', 'center');
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testPadDown(): void
    {
        $img = $this->abstractImageMock();

        $modifier = Mockery::mock(ModifierInterface::class);
        $modifier->shouldReceive('apply')->with($img)->andReturn($img);

        $img->shouldReceive('resolveDriverClass')
            ->with('Modifiers\PadDownModifier', 200, 100, 'ffffff', 'center')
            ->andReturn($modifier);

        $result = $img->padDown(200, 100, 'ffffff', 'center');
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testDestroy(): void
    {
        $img = $this->abstractImageMock();

        $modifier = Mockery::mock(ModifierInterface::class);
        $modifier->shouldReceive('apply')->with($img)->andReturn($img);

        $img->shouldReceive('resolveDriverClass')
            ->with('Modifiers\DestroyModifier')
            ->andReturn($modifier);

        $img->destroy();
    }
}
