<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use Intervention\Image\Collection;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Exceptions\EncoderException;
use Intervention\Image\Fraction;
use Intervention\Image\Geometry\Bezier;
use Intervention\Image\Geometry\Circle;
use Intervention\Image\Geometry\Ellipse;
use Intervention\Image\Geometry\Line;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Geometry\Polygon;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Origin;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Image::class)]
final class ImageTest extends BaseTestCase
{
    private function createImage(int $width = 100, int $height = 100): Image
    {
        $driver = new GdDriver();
        return $driver->createImage($width, $height);
    }

    public function testConstructor(): void
    {
        $image = $this->createImage();
        $this->assertInstanceOf(Image::class, $image);
    }

    public function testDriver(): void
    {
        $image = $this->createImage();
        $this->assertInstanceOf(GdDriver::class, $image->driver());
    }

    public function testCore(): void
    {
        $image = $this->createImage();
        $this->assertNotNull($image->core());
    }

    public function testOrigin(): void
    {
        $image = $this->createImage();
        $this->assertInstanceOf(Origin::class, $image->origin());
    }

    public function testSetOrigin(): void
    {
        $image = $this->createImage();
        $origin = new Origin();
        $origin->setFilePath('/tmp/test.jpg');
        $result = $image->setOrigin($origin);
        $this->assertSame($image, $result);
        $this->assertSame($origin, $image->origin());
        $this->assertEquals('/tmp/test.jpg', $image->origin()->filePath());
    }

    public function testCount(): void
    {
        $image = $this->createImage();
        $this->assertEquals(1, $image->count());
    }

    public function testGetIterator(): void
    {
        $image = $this->createImage();
        $count = 0;
        foreach ($image as $frame) {
            $this->assertInstanceOf(\Intervention\Image\Interfaces\FrameInterface::class, $frame);
            $count++;
        }
        $this->assertEquals(1, $count);
    }

    public function testIsAnimated(): void
    {
        $image = $this->createImage();
        $this->assertFalse($image->isAnimated());
    }

    public function testLoops(): void
    {
        $image = $this->createImage();
        $this->assertEquals(0, $image->loops());
    }

    public function testSetLoops(): void
    {
        $image = $this->createImage();
        $result = $image->setLoops(5);
        $this->assertSame($image, $result);
        $this->assertEquals(5, $image->loops());
    }

    public function testExif(): void
    {
        $image = $this->createImage();
        $exif = $image->exif();
        $this->assertInstanceOf(\Intervention\Image\Interfaces\CollectionInterface::class, $exif);
    }

    public function testExifWithQuery(): void
    {
        $image = $this->createImage();
        $this->assertNull($image->exif('nonexistent'));
    }

    public function testSetExif(): void
    {
        $image = $this->createImage();
        $exif = new Collection(['foo' => 'bar']);
        $result = $image->setExif($exif);
        $this->assertSame($image, $result);
        $this->assertEquals('bar', $image->exif('foo'));
    }

    public function testWidth(): void
    {
        $image = $this->createImage(200, 100);
        $this->assertEquals(200, $image->width());
    }

    public function testHeight(): void
    {
        $image = $this->createImage(200, 100);
        $this->assertEquals(100, $image->height());
    }

    public function testSize(): void
    {
        $image = $this->createImage(200, 100);
        $size = $image->size();
        $this->assertInstanceOf(SizeInterface::class, $size);
        $this->assertEquals(200, $size->width());
        $this->assertEquals(100, $size->height());
    }

    public function testResize(): void
    {
        $image = $this->createImage(100, 100);
        $result = $image->resize(50, 50);
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertEquals(50, $image->width());
        $this->assertEquals(50, $image->height());
    }

    public function testResizeWithFraction(): void
    {
        $image = $this->createImage(100, 100);
        $result = $image->resize(Fraction::HALF, null);
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertEquals(50, $image->width());
    }

    public function testResizeDown(): void
    {
        $image = $this->createImage(100, 100);
        $result = $image->resizeDown(50, 50);
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertEquals(50, $image->width());
        $this->assertEquals(50, $image->height());
    }

    public function testScale(): void
    {
        $image = $this->createImage(100, 100);
        $result = $image->scale(width: 50);
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertEquals(50, $image->width());
        $this->assertEquals(50, $image->height());
    }

    public function testScaleDown(): void
    {
        $image = $this->createImage(100, 100);
        $result = $image->scaleDown(width: 50);
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertEquals(50, $image->width());
        $this->assertEquals(50, $image->height());
    }

    public function testCover(): void
    {
        $image = $this->createImage(100, 100);
        $result = $image->cover(50, 50);
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertEquals(50, $image->width());
        $this->assertEquals(50, $image->height());
    }

    public function testCoverDown(): void
    {
        $image = $this->createImage(100, 100);
        $result = $image->coverDown(50, 50);
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertEquals(50, $image->width());
        $this->assertEquals(50, $image->height());
    }

    public function testCrop(): void
    {
        $image = $this->createImage(100, 100);
        $result = $image->crop(50, 50);
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertEquals(50, $image->width());
        $this->assertEquals(50, $image->height());
    }

    public function testPad(): void
    {
        $image = $this->createImage(50, 50);
        $result = $image->pad(100, 100, 'ffffff');
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertEquals(100, $image->width());
        $this->assertEquals(100, $image->height());
    }

    public function testContain(): void
    {
        $image = $this->createImage(100, 50);
        $result = $image->contain(50, 50, 'ffffff');
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testResizeCanvas(): void
    {
        $image = $this->createImage(100, 100);
        $result = $image->resizeCanvas(150, 150, 'ffffff');
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertEquals(150, $image->width());
        $this->assertEquals(150, $image->height());
    }

    public function testResizeCanvasRelative(): void
    {
        $image = $this->createImage(100, 100);
        $result = $image->resizeCanvasRelative(50, 50, 'ffffff');
        $this->assertInstanceOf(ImageInterface::class, $result);
        $this->assertEquals(150, $image->width());
        $this->assertEquals(150, $image->height());
    }

    public function testFlip(): void
    {
        $image = $this->createImage();
        $result = $image->flip();
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testRotate(): void
    {
        $image = $this->createImage();
        $result = $image->rotate(90);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testGamma(): void
    {
        $image = $this->createImage();
        $result = $image->gamma(1.5);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testBrightness(): void
    {
        $image = $this->createImage();
        $result = $image->brightness(10);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testContrast(): void
    {
        $image = $this->createImage();
        $result = $image->contrast(10);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testColorize(): void
    {
        $image = $this->createImage();
        $result = $image->colorize(10, 0, 0);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testSharpen(): void
    {
        $image = $this->createImage();
        $result = $image->sharpen(10);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testBlur(): void
    {
        $image = $this->createImage();
        $result = $image->blur(5);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testInvert(): void
    {
        $image = $this->createImage();
        $result = $image->invert();
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testPixelate(): void
    {
        $image = $this->createImage();
        $result = $image->pixelate(10);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testGrayscale(): void
    {
        $image = $this->createImage();
        $result = $image->grayscale();
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testOrient(): void
    {
        $image = $this->createImage();
        $result = $image->orient();
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testFill(): void
    {
        $image = $this->createImage();
        $result = $image->fill('ff0000');
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testFillWithPosition(): void
    {
        $image = $this->createImage();
        $result = $image->fill('ff0000', 0, 0);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testDrawPixel(): void
    {
        $image = $this->createImage();
        $result = $image->drawPixel(10, 10, 'ff0000');
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testDrawRectangle(): void
    {
        $image = $this->createImage();
        $result = $image->drawRectangle(function ($rectangle): void {
            $rectangle->size(50, 50);
            $rectangle->at(10, 10);
            $rectangle->background('ff0000');
        });
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testDrawRectangleWithObject(): void
    {
        $image = $this->createImage();
        $rectangle = new Rectangle(50, 50, new Point(10, 10));
        $rectangle->setBackgroundColor('ff0000');
        $result = $image->drawRectangle($rectangle);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testDrawEllipse(): void
    {
        $image = $this->createImage();
        $result = $image->drawEllipse(function ($ellipse): void {
            $ellipse->size(50, 30);
            $ellipse->at(50, 50);
            $ellipse->background('ff0000');
        });
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testDrawCircle(): void
    {
        $image = $this->createImage();
        $result = $image->drawCircle(function ($circle): void {
            $circle->radius(25);
            $circle->at(50, 50);
            $circle->background('ff0000');
        });
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testDrawLine(): void
    {
        $image = $this->createImage();
        $result = $image->drawLine(function ($line): void {
            $line->from(0, 0);
            $line->to(100, 100);
            $line->color('ff0000');
        });
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testDrawPolygon(): void
    {
        $image = $this->createImage();
        $result = $image->drawPolygon(function ($polygon): void {
            $polygon->point(10, 10);
            $polygon->point(90, 10);
            $polygon->point(50, 90);
            $polygon->background('ff0000');
        });
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testDrawBezier(): void
    {
        $image = $this->createImage();
        $bezier = new Bezier([
            new Point(10, 10),
            new Point(50, 50),
            new Point(90, 10),
        ]);
        $result = $image->drawBezier($bezier);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testDrawWithRectangle(): void
    {
        $image = $this->createImage();
        $rectangle = new Rectangle(50, 50, new Point(10, 10));
        $rectangle->setBackgroundColor('ff0000');
        $result = $image->draw($rectangle);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testDrawWithEllipse(): void
    {
        $image = $this->createImage();
        $ellipse = new Ellipse(50, 30, new Point(50, 50));
        $ellipse->setBackgroundColor('ff0000');
        $result = $image->draw($ellipse);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testDrawWithCircle(): void
    {
        $image = $this->createImage();
        $circle = new Circle(50, new Point(50, 50));
        $circle->setBackgroundColor('ff0000');
        $result = $image->draw($circle);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testDrawWithLine(): void
    {
        $image = $this->createImage();
        $line = new Line(new Point(0, 0), new Point(100, 100));
        $result = $image->draw($line);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testDrawWithPolygon(): void
    {
        $image = $this->createImage();
        $polygon = new Polygon([
            new Point(10, 10),
            new Point(90, 10),
            new Point(50, 90),
        ]);
        $polygon->setBackgroundColor('ff0000');
        $result = $image->draw($polygon);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testDrawWithBezier(): void
    {
        $image = $this->createImage();
        $bezier = new Bezier([
            new Point(10, 10),
            new Point(50, 50),
            new Point(90, 10),
        ]);
        $result = $image->draw($bezier);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testDrawWithAdjustments(): void
    {
        $image = $this->createImage();
        $rectangle = new Rectangle(50, 50, new Point(10, 10));
        $result = $image->draw($rectangle, function ($factory): void {
            $factory->background('ff0000');
        });
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testColorAt(): void
    {
        $image = $this->createImage();
        $color = $image->colorAt(0, 0);
        $this->assertInstanceOf(ColorInterface::class, $color);
    }

    public function testRemoveAnimation(): void
    {
        $image = $this->createImage();
        $result = $image->removeAnimation();
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testTrim(): void
    {
        $image = $this->createImage();
        $result = $image->trim(0);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testReduceColors(): void
    {
        $image = $this->createImage();
        $result = $image->reduceColors(16);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testInsert(): void
    {
        $image = $this->createImage();
        $other = $this->createImage(50, 50);
        $result = $image->insert($other);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testBackgroundColor(): void
    {
        $image = $this->createImage();
        $color = $image->backgroundColor();
        $this->assertInstanceOf(ColorInterface::class, $color);
    }

    public function testSetBackgroundColor(): void
    {
        $image = $this->createImage();
        $result = $image->setBackgroundColor('ff0000');
        $this->assertSame($image, $result);
    }

    public function testEncode(): void
    {
        $image = $this->createImage();
        $encoded = $image->encode(new \Intervention\Image\Encoders\PngEncoder());
        $this->assertNotEmpty($encoded->toString());
    }

    public function testEncodeUsingFormat(): void
    {
        $image = $this->createImage();
        $encoded = $image->encodeUsingFormat(\Intervention\Image\Format::PNG);
        $this->assertNotEmpty($encoded->toString());
    }

    public function testEncodeUsingMediaType(): void
    {
        $image = $this->createImage();
        $encoded = $image->encodeUsingMediaType('image/png');
        $this->assertNotEmpty($encoded->toString());
    }

    public function testEncodeUsingFileExtension(): void
    {
        $image = $this->createImage();
        $encoded = $image->encodeUsingFileExtension('png');
        $this->assertNotEmpty($encoded->toString());
    }

    public function testEncodeUsingPath(): void
    {
        $image = $this->createImage();
        $encoded = $image->encodeUsingPath('/tmp/test.png');
        $this->assertNotEmpty($encoded->toString());
    }

    public function testSave(): void
    {
        $image = $this->createImage();
        $path = sys_get_temp_dir() . '/intervention_test_' . hrtime(true) . '.png';

        try {
            $result = $image->save($path);
            $this->assertInstanceOf(ImageInterface::class, $result);
            $this->assertFileExists($path);
        } finally {
            if (is_file($path)) {
                unlink($path);
            }
        }
    }

    public function testSaveWithoutPathAndNoOrigin(): void
    {
        $image = $this->createImage();
        $this->expectException(EncoderException::class);
        $image->save();
    }

    public function testSaveWithOriginPath(): void
    {
        $image = $this->createImage();
        $path = sys_get_temp_dir() . '/intervention_test_' . hrtime(true) . '.png';
        $origin = new Origin();
        $origin->setFilePath($path);
        $image->setOrigin($origin);

        try {
            $result = $image->save();
            $this->assertInstanceOf(ImageInterface::class, $result);
            $this->assertFileExists($path);
        } finally {
            if (is_file($path)) {
                unlink($path);
            }
        }
    }

    public function testFillTransparentAreas(): void
    {
        $image = $this->createImage();
        $result = $image->fillTransparentAreas('ffffff');
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testDebugInfo(): void
    {
        $image = $this->createImage(200, 100);
        $info = $image->__debugInfo();
        $this->assertEquals(['width' => 200, 'height' => 100], $info);
    }

    public function testClone(): void
    {
        $image = $this->createImage(100, 100);
        $clone = clone $image;

        // They should be separate instances
        $this->assertNotSame($image->driver(), $clone->driver());
        $this->assertNotSame($image->core(), $clone->core());

        // But same dimensions
        $this->assertEquals($image->width(), $clone->width());
        $this->assertEquals($image->height(), $clone->height());

        // Modifying clone should not affect original
        $clone->resize(50, 50);
        $this->assertEquals(100, $image->width());
        $this->assertEquals(50, $clone->width());
    }

    public function testSliceAnimation(): void
    {
        $image = $this->createImage();
        $result = $image->sliceAnimation(0);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testSetResolution(): void
    {
        $image = $this->createImage();
        $result = $image->setResolution(150, 150);
        $this->assertInstanceOf(ImageInterface::class, $result);
    }

    public function testColorspace(): void
    {
        $image = $this->createImage();
        $colorspace = $image->colorspace();
        $this->assertInstanceOf(\Intervention\Image\Interfaces\ColorspaceInterface::class, $colorspace);
    }

    public function testResolution(): void
    {
        $image = $this->createImage();
        $resolution = $image->resolution();
        $this->assertInstanceOf(\Intervention\Image\Interfaces\ResolutionInterface::class, $resolution);
    }
}
