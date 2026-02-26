<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick;

use Imagick;
use Intervention\Image\Alignment;
use Intervention\Image\Analyzers\WidthAnalyzer;
use Intervention\Image\Collection;
use Intervention\Image\Colors\Cmyk\Colorspace as CmykColorspace;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Direction;
use Intervention\Image\Drivers\Imagick\Core;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Drivers\Imagick\Frame;
use Intervention\Image\EncodedImage;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Exceptions\AnalyzerException;
use Intervention\Image\Exceptions\EncoderException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Format;
use Intervention\Image\Fraction;
use Intervention\Image\Geometry\Bezier;
use Intervention\Image\Geometry\Circle;
use Intervention\Image\Geometry\Ellipse;
use Intervention\Image\Geometry\Line;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Geometry\Rectangle;
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
        $result = $image->fillTransparentAreas();
        $this->assertColor(255, 255, 255, 255, $image->colorAt(1, 0));
        $this->assertColor(255, 255, 255, 255, $result->colorAt(1, 0));
    }

    public function testBackgroundArgument(): void
    {
        $image = $this->readTestImage('gradient.gif');
        $this->assertColor(0, 0, 0, 0, $image->colorAt(1, 0));
        $result = $image->fillTransparentAreas('ff5500');
        $this->assertColor(255, 85, 0, 255, $image->colorAt(1, 0));
        $this->assertColor(255, 85, 0, 255, $result->colorAt(1, 0));
    }

    public function testBackgroundIgnoreTransparencyInBackgroundColor(): void
    {
        $image = $this->readTestImage('gradient.gif');
        $this->assertColor(0, 0, 0, 0, $image->colorAt(1, 0));
        $result = $image->fillTransparentAreas('ff550033');
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

    public function testContrast(): void
    {
        $image = $this->readTestImage('trim.png');
        $result = $image->contrast(10);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testGamma(): void
    {
        $image = $this->readTestImage('trim.png');
        $result = $image->gamma(1.5);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testColorize(): void
    {
        $image = $this->readTestImage('trim.png');
        $result = $image->colorize(10, 20, 30);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testFlip(): void
    {
        $image = $this->readTestImage('trim.png');
        $result = $image->flip();
        $this->assertInstanceOf(ImageInterface::class, $result);

        $result = $image->flip(Direction::VERTICAL);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testBlur(): void
    {
        $image = $this->readTestImage('trim.png');
        $result = $image->blur(5);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testRotate(): void
    {
        $image = $this->readTestImage('trim.png');
        $result = $image->rotate(45);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testOrient(): void
    {
        $image = $this->readTestImage('trim.png');
        $result = $image->orient();
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testTrim(): void
    {
        $image = $this->readTestImage('trim.png');
        $result = $image->trim(10);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testInsert(): void
    {
        $image = $this->readTestImage('trim.png');
        $watermark = $this->createTestImage(5, 5);
        $result = $image->insert($watermark);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testInsertWithAlignment(): void
    {
        $image = $this->readTestImage('trim.png');
        $watermark = $this->createTestImage(5, 5);
        $result = $image->insert($watermark, 10, 10, Alignment::CENTER, 50);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testFill(): void
    {
        $image = $this->readTestImage('trim.png');
        $result = $image->fill('ff0000');
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testFillAtPosition(): void
    {
        $image = $this->readTestImage('trim.png');
        $result = $image->fill('ff0000', 0, 0);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testResize(): void
    {
        $image = $this->readTestImage('trim.png');
        $result = $image->resize(100, 100);
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertEquals(100, $result->width());
        $this->assertEquals(100, $result->height());
    }

    public function testResizeWithFraction(): void
    {
        $image = $this->readTestImage('trim.png');
        $originalWidth = $image->width();
        $originalHeight = $image->height();
        $result = $image->resize(Fraction::HALF, Fraction::HALF);
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertEquals((int) round($originalWidth * 0.5), $result->width());
        $this->assertEquals((int) round($originalHeight * 0.5), $result->height());
    }

    public function testResizeDown(): void
    {
        $image = $this->readTestImage('trim.png');
        $result = $image->resizeDown(100, 100);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testScale(): void
    {
        $image = $this->readTestImage('trim.png');
        $result = $image->scale(100);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testScaleWithFraction(): void
    {
        $image = $this->readTestImage('trim.png');
        $result = $image->scale(Fraction::DOUBLE);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testScaleDown(): void
    {
        $image = $this->readTestImage('trim.png');
        $result = $image->scaleDown(100);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testCover(): void
    {
        $image = $this->readTestImage('trim.png');
        $result = $image->cover(10, 10);
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertEquals(10, $result->width());
        $this->assertEquals(10, $result->height());
    }

    public function testCoverWithFraction(): void
    {
        $image = $this->readTestImage('trim.png');
        $result = $image->cover(Fraction::HALF, Fraction::HALF);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testCoverDown(): void
    {
        $image = $this->readTestImage('trim.png');
        $result = $image->coverDown(10, 10);
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertEquals(10, $result->width());
        $this->assertEquals(10, $result->height());
    }

    public function testPad(): void
    {
        $image = $this->readTestImage('trim.png');
        $result = $image->pad(100, 100);
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertEquals(100, $result->width());
        $this->assertEquals(100, $result->height());
    }

    public function testPadWithBackground(): void
    {
        $image = $this->readTestImage('trim.png');
        $result = $image->pad(100, 100, 'ff0000', Alignment::CENTER);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testContain(): void
    {
        $image = $this->readTestImage('trim.png');
        $result = $image->contain(100, 100);
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertEquals(100, $result->width());
        $this->assertEquals(100, $result->height());
    }

    public function testContainWithBackground(): void
    {
        $image = $this->readTestImage('trim.png');
        $result = $image->contain(100, 100, 'ff0000', Alignment::CENTER);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testCropWithAlignment(): void
    {
        $image = $this->readTestImage('trim.png');
        $result = $image->crop(10, 10, 0, 0, null, Alignment::CENTER);
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertEquals(10, $result->width());
        $this->assertEquals(10, $result->height());
    }

    public function testCropWithFraction(): void
    {
        $image = $this->readTestImage('trim.png');
        $result = $image->crop(Fraction::HALF, Fraction::HALF);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testResizeCanvas(): void
    {
        $image = $this->readTestImage('trim.png');
        $result = $image->resizeCanvas(100, 100);
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertEquals(100, $result->width());
        $this->assertEquals(100, $result->height());
    }

    public function testResizeCanvasWithBackground(): void
    {
        $image = $this->readTestImage('trim.png');
        $result = $image->resizeCanvas(100, 100, 'ff0000', Alignment::CENTER);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testResizeCanvasRelative(): void
    {
        $image = $this->readTestImage('trim.png');
        $originalWidth = $image->width();
        $originalHeight = $image->height();
        $result = $image->resizeCanvasRelative(10, 10);
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertEquals($originalWidth + 10, $result->width());
        $this->assertEquals($originalHeight + 10, $result->height());
    }

    public function testResizeCanvasRelativeWithBackground(): void
    {
        $image = $this->readTestImage('trim.png');
        $result = $image->resizeCanvasRelative(10, 10, 'ff0000', Alignment::CENTER);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testDrawPixel(): void
    {
        $image = $this->createTestImage(10, 10);
        $result = $image->drawPixel(5, 5, 'ff0000');
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testDrawRectangle(): void
    {
        $image = $this->createTestImage(10, 10);
        $result = $image->drawRectangle(function ($rectangle): void {
            $rectangle->size(5, 5);
            $rectangle->background('ff0000');
        });
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testDrawRectangleObject(): void
    {
        $image = $this->createTestImage(10, 10);
        $rect = new Rectangle(5, 5, new Point(0, 0));
        $result = $image->drawRectangle($rect);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testDrawEllipse(): void
    {
        $image = $this->createTestImage(10, 10);
        $result = $image->drawEllipse(function ($ellipse): void {
            $ellipse->size(6, 4);
            $ellipse->background('ff0000');
        });
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testDrawCircle(): void
    {
        $image = $this->createTestImage(10, 10);
        $result = $image->drawCircle(function ($circle): void {
            $circle->radius(3);
            $circle->background('ff0000');
        });
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testDrawPolygon(): void
    {
        $image = $this->createTestImage(10, 10);
        $result = $image->drawPolygon(function ($polygon): void {
            $polygon->point(0, 0);
            $polygon->point(5, 0);
            $polygon->point(5, 5);
        });
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testDrawLine(): void
    {
        $image = $this->createTestImage(10, 10);
        $result = $image->drawLine(function ($line): void {
            $line->from(0, 0);
            $line->to(9, 9);
            $line->color('ff0000');
        });
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testDrawBezier(): void
    {
        $image = $this->createTestImage(10, 10);
        $result = $image->drawBezier(function ($bezier): void {
            $bezier->point(0, 0);
            $bezier->point(3, 5);
            $bezier->point(6, 2);
            $bezier->point(9, 9);
        });
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testDrawWithRectangle(): void
    {
        $image = $this->createTestImage(10, 10);
        $rect = new Rectangle(5, 5, new Point(0, 0));
        $result = $image->draw($rect);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testDrawWithEllipse(): void
    {
        $image = $this->createTestImage(10, 10);
        $ellipse = new Ellipse(6, 4, new Point(5, 5));
        $result = $image->draw($ellipse);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testDrawWithCircle(): void
    {
        $image = $this->createTestImage(10, 10);
        $circle = new Circle(6, new Point(5, 5));
        $result = $image->draw($circle);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testDrawWithLine(): void
    {
        $image = $this->createTestImage(10, 10);
        $line = new Line(new Point(0, 0), new Point(9, 9));
        $result = $image->draw($line);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testDrawWithBezier(): void
    {
        $image = $this->createTestImage(10, 10);
        $bezier = new Bezier([new Point(0, 0), new Point(3, 5), new Point(6, 2), new Point(9, 9)]);
        $result = $image->draw($bezier);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testDrawWithPolygon(): void
    {
        $image = $this->createTestImage(10, 10);
        $polygon = new Polygon([new Point(0, 0), new Point(5, 0), new Point(5, 5)]);
        $result = $image->draw($polygon);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testDrawWithAdjustments(): void
    {
        $image = $this->createTestImage(10, 10);
        $rect = new Rectangle(5, 5, new Point(0, 0));
        $result = $image->draw($rect, function ($factory): void {
            $factory->background('ff0000');
        });
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testEncodeUsingFormat(): void
    {
        $image = $this->readTestImage('blue.gif');
        $result = $image->encodeUsingFormat(Format::PNG);
        $this->assertInstanceOf(EncodedImage::class, $result);
        $this->assertMediaType('image/png', $result);
    }

    public function testBackgroundColor(): void
    {
        $image = $this->readTestImage('trim.png');
        $result = $image->backgroundColor();
        $this->assertInstanceOf(ColorInterface::class, $result);
    }

    public function testSetBackgroundColor(): void
    {
        $image = $this->readTestImage('trim.png');
        $result = $image->setBackgroundColor('ff0000');
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testRemoveProfile(): void
    {
        $image = $this->readTestImage('trim.png');
        $result = $image->removeProfile();
        $this->assertInstanceOf(ImageInterface::class, $result);
    }
}
