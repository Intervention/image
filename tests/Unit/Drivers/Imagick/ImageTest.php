<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick;

use Imagick;
use Intervention\Image\Analyzers\WidthAnalyzer;
use Intervention\Image\Collection;
use Intervention\Image\Colors\Cmyk\Colorspace as CmykColorspace;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Drivers\Imagick\Core;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Drivers\Imagick\Frame;
use Intervention\Image\EncodedImage;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Exceptions\AnalyzerException;
use Intervention\Image\Exceptions\EncoderException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ResolutionInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Modifiers\GrayscaleModifier;
use Intervention\Image\Origin;
use Intervention\Image\Tests\ImagickTestCase;
use Intervention\Image\Tests\Resource;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('imagick')]
#[CoversClass(Image::class)]
final class ImageTest extends ImagickTestCase
{
    protected Image $image;

    protected function setUp(): void
    {
        $imagick = new Imagick();
        $imagick->readImage(Resource::create('animation.gif')->path());
        $this->image = (new Image(
            new Driver(),
            new Core($imagick),
        ))->setExif(
            new Collection([
                'test' => 'foo'
            ])
        );
    }

    public function testClone(): void
    {
        $image = $this->readTestImage('gradient.gif');
        $clone = clone $image;

        $this->assertEquals(16, $image->width());
        $this->assertEquals(16, $clone->width());
        $result = $clone->crop(4, 4);
        $this->assertEquals(16, $image->width());
        $this->assertEquals(4, $clone->width());
        $this->assertEquals(4, $result->width());

        $this->assertEquals('ff0000', $image->colorAt(0, 0)->toHex());
        $this->assertTransparency($image->colorAt(1, 0));

        $this->assertEquals('ff0000', $clone->colorAt(0, 0)->toHex());
        $this->assertTransparency($clone->colorAt(1, 0));
    }

    public function testDriver(): void
    {
        $this->assertInstanceOf(Driver::class, $this->image->driver());
    }

    public function testCore(): void
    {
        $this->assertInstanceOf(Core::class, $this->image->core());
    }

    public function testCount(): void
    {
        $this->assertEquals(8, $this->image->count());
    }

    public function testIteration(): void
    {
        foreach ($this->image as $frame) {
            $this->assertInstanceOf(Frame::class, $frame);
        }
    }

    public function testIsAnimated(): void
    {
        $this->assertTrue($this->image->isAnimated());
    }

    public function testSetGetLoops(): void
    {
        $this->assertEquals(3, $this->image->loops());
        $result = $this->image->setLoops(10);
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertEquals(10, $this->image->loops());
    }

    public function testSetGetOrigin(): void
    {
        $origin = $this->image->origin();
        $this->assertInstanceOf(Origin::class, $origin);
        $this->image->setOrigin(new Origin('test1', 'test2'));
        $this->assertInstanceOf(Origin::class, $this->image->origin());
        $this->assertEquals('test1', $this->image->origin()->mimetype());
        $this->assertEquals('test2', $this->image->origin()->filePath());
    }

    public function testRemoveAnimation(): void
    {
        $this->assertTrue($this->image->isAnimated());
        $result = $this->image->removeAnimation();
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertFalse($this->image->isAnimated());
    }

    public function testSliceAnimation(): void
    {
        $this->assertEquals(8, $this->image->count());
        $result = $this->image->sliceAnimation(0, 2);
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertEquals(2, $this->image->count());
    }

    public function testExif(): void
    {
        $this->assertInstanceOf(Collection::class, $this->image->exif());
        $this->assertEquals('foo', $this->image->exif('test'));
    }

    public function testModify(): void
    {
        $result = $this->image->modify(new GrayscaleModifier());
        $this->assertInstanceOf(Image::class, $result);
    }

    public function testAnalyze(): void
    {
        $result = $this->image->analyze(new WidthAnalyzer());
        $this->assertEquals(20, $result);
    }

    public function testEncode(): void
    {
        $result = $this->image->encode(new PngEncoder());
        $this->assertInstanceOf(EncodedImage::class, $result);
    }

    public function testAutoEncode(): void
    {
        $result = $this->readTestImage('blue.gif')->encode();
        $this->assertInstanceOf(EncodedImage::class, $result);
        $this->assertMediaType('image/gif', $result);
    }

    public function testEncodeByMediaType(): void
    {
        $result = $this->readTestImage('blue.gif')->encodeUsingMediaType('image/png');
        $this->assertInstanceOf(EncodedImage::class, $result);
        $this->assertMediaType('image/png', $result);
    }

    public function testEncodeByExtension(): void
    {
        $result = $this->readTestImage('blue.gif')->encodeUsingFileExtension('png');
        $this->assertInstanceOf(EncodedImage::class, $result);
        $this->assertMediaType('image/png', $result);
    }

    public function testEncodeByPath(): void
    {
        $result = $this->readTestImage('blue.gif')->encodeUsingPath('foo/bar.png');
        $this->assertInstanceOf(EncodedImage::class, $result);
        $this->assertMediaType('image/png', $result);
    }

    public function testSaveAsFormat(): void
    {
        $path = __DIR__ . '/tmp.png';
        $result = $this->readTestImage('blue.gif')->save($path);
        $this->assertInstanceOf(Image::class, $result);
        $this->assertFileExists($path);
        $this->assertMediaType('image/png', file_get_contents($path));
        unlink($path);
    }

    public function testSaveFallback(): void
    {
        $path = __DIR__ . '/tmp.unknown';
        $this->expectException(NotSupportedException::class);
        $this->readTestImage('blue.gif')->save($path);
    }

    public function testSaveUndeterminedPath(): void
    {
        $this->expectException(EncoderException::class);
        $this->createTestImage(2, 3)->save();
    }

    public function testWidthHeightSize(): void
    {
        $this->assertEquals(20, $this->image->width());
        $this->assertEquals(15, $this->image->height());
        $this->assertInstanceOf(SizeInterface::class, $this->image->size());
    }

    public function testSetGetColorspace(): void
    {
        $this->assertInstanceOf(ColorspaceInterface::class, $this->image->colorspace());
        $this->assertInstanceOf(RgbColorspace::class, $this->image->colorspace());
        $result = $this->image->setColorspace(CmykColorspace::class);
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertInstanceOf(CmykColorspace::class, $this->image->colorspace());
    }

    public function testSetGetResolution(): void
    {
        $resolution = $this->image->resolution();
        $this->assertInstanceOf(ResolutionInterface::class, $resolution);
        $this->assertEquals(0, $resolution->x());
        $this->assertEquals(0, $resolution->y());
        $result = $this->image->setResolution(300, 300);
        $resolution = $this->image->resolution();
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertEquals(300, $resolution->x());
        $this->assertEquals(300, $resolution->y());
    }

    public function testPickColor(): void
    {
        $this->assertInstanceOf(ColorInterface::class, $this->image->colorAt(0, 0));
        $this->assertInstanceOf(ColorInterface::class, $this->image->colorAt(0, 0, 1));
    }

    public function testPickColors(): void
    {
        $result = $this->image->colorsAt(0, 0);
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEquals(8, $result->count());
    }

    public function testProfile(): void
    {
        $this->expectException(AnalyzerException::class);
        $this->image->profile();
    }

    public function testReduceColors(): void
    {
        $image = $this->readTestImage();
        $result = $image->reduceColors(8);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testSharpen(): void
    {
        $this->assertInstanceOf(Image::class, $this->image->sharpen(12));
    }

    public function testBackgroundDefault(): void
    {
        $image = $this->readTestImage('gradient.gif');
        $this->assertColor(0, 0, 0, 0, $image->colorAt(1, 0));
        $result = $image->background();
        $this->assertColor(255, 255, 255, 255, $image->colorAt(1, 0));
        $this->assertColor(255, 255, 255, 255, $result->colorAt(1, 0));
    }

    public function testBackgroundArgument(): void
    {
        $image = $this->readTestImage('gradient.gif');
        $this->assertColor(0, 0, 0, 0, $image->colorAt(1, 0));
        $result = $image->background('ff5500');
        $this->assertColor(255, 85, 0, 255, $image->colorAt(1, 0));
        $this->assertColor(255, 85, 0, 255, $result->colorAt(1, 0));
    }

    public function testBackgroundIgnoreTransparencyInBackgroundColor(): void
    {
        $image = $this->readTestImage('gradient.gif');
        $this->assertColor(0, 0, 0, 0, $image->colorAt(1, 0));
        $result = $image->background('ff550033');
        $this->assertColor(255, 85, 0, 51, $image->colorAt(1, 0), 1);
        $this->assertColor(255, 85, 0, 51, $result->colorAt(1, 0), 1);
    }

    public function testInvert(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertEquals('00aef0', $image->colorAt(0, 0)->toHex());
        $this->assertEquals('ffa601', $image->colorAt(25, 25)->toHex());
        $result = $image->invert();
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertEquals('ff510f', $image->colorAt(0, 0)->toHex());
        $this->assertEquals('0059fe', $image->colorAt(25, 25)->toHex());
    }

    public function testPixelate(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertEquals('00aef0', $image->colorAt(0, 0)->toHex());
        $this->assertEquals('00aef0', $image->colorAt(14, 14)->toHex());

        $result = $image->pixelate(10);
        $this->assertInstanceOf(ImageInterface::class, $result);

        $this->assertEquals([0, 174, 240, 255], array_map(
            fn(ColorChannelInterface $channel): int => $channel->value(),
            $image->colorAt(0, 0)->channels()
        ));

        $this->assertEquals([107, 171, 140, 255], array_map(
            fn(ColorChannelInterface $channel): int => $channel->value(),
            $image->colorAt(14, 14)->channels()
        ));
    }

    public function testGrayscale(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertFalse($image->colorAt(0, 0)->isGrayscale());
        $result = $image->grayscale();
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertTrue($image->colorAt(0, 0)->isGrayscale());
    }

    public function testBrightness(): void
    {
        $image = $this->readTestImage('trim.png');
        $this->assertEquals('00aef0', $image->colorAt(14, 14)->toHex());
        $result = $image->brightness(30);
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertEquals('39c9ff', $image->colorAt(14, 14)->toHex());
    }

    public function testDebugInfo(): void
    {
        $info = $this->readTestImage('trim.png')->__debugInfo();
        $this->assertArrayHasKey('width', $info);
        $this->assertArrayHasKey('height', $info);
    }
}
