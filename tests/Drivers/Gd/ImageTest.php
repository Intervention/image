<?php

namespace Intervention\Image\Tests\Drivers\Gd;

use Intervention\Image\Analyzers\WidthAnalyzer;
use Intervention\Image\Collection;
use Intervention\Image\Drivers\Gd\Core;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\EncodedImage;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ColorspaceInterface;
use Intervention\Image\Interfaces\ResolutionInterface;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Modifiers\GreyscaleModifier;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateGdTestImage;
use Intervention\Image\Typography\Font;

class ImageTest extends TestCase
{
    use CanCreateGdTestImage;

    protected Image $image;

    public function setUp(): void
    {
        $this->image = new Image(
            new Driver(),
            new Core([
                new Frame(imagecreatetruecolor(3, 2)),
                new Frame(imagecreatetruecolor(3, 2)),
            ]),
            new Collection([
                'test' => 'foo'
            ]),
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

        $this->assertEquals('ff0000', $image->pickColor(0, 0)->toHex());
        $this->assertEquals('00000000', $image->pickColor(1, 0)->toHex());

        $this->assertEquals('ff0000', $clone->pickColor(0, 0)->toHex());
        $this->assertEquals('00000000', $clone->pickColor(1, 0)->toHex());
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
        $this->assertEquals(2, $this->image->count());
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

    public function testLoops(): void
    {
        $this->assertEquals(0, $this->image->loops());
    }

    public function testExif(): void
    {
        $this->assertInstanceOf(Collection::class, $this->image->exif());
        $this->assertEquals('foo', $this->image->exif('test'));
    }

    public function testModify(): void
    {
        $result = $this->image->modify(new GreyscaleModifier());
        $this->assertInstanceOf(Image::class, $result);
    }

    public function testAnalyze(): void
    {
        $result = $this->image->analyze(new WidthAnalyzer());
        $this->assertEquals(3, $result);
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
        $this->assertMediaType('image/gif', (string) $result);
    }

    public function testEncodeByMediaType(): void
    {
        $result = $this->readTestImage('blue.gif')->encodeByMediaType();
        $this->assertInstanceOf(EncodedImage::class, $result);
        $this->assertMediaType('image/gif', (string) $result);

        $result = $this->readTestImage('blue.gif')->encodeByMediaType('image/png');
        $this->assertInstanceOf(EncodedImage::class, $result);
        $this->assertMediaType('image/png', (string) $result);
    }

    public function testEncodeByExtension(): void
    {
        $result = $this->readTestImage('blue.gif')->encodeByExtension();
        $this->assertInstanceOf(EncodedImage::class, $result);
        $this->assertMediaType('image/gif', (string) $result);

        $result = $this->readTestImage('blue.gif')->encodeByExtension('png');
        $this->assertInstanceOf(EncodedImage::class, $result);
        $this->assertMediaType('image/png', (string) $result);
    }

    public function testWidthHeightSize(): void
    {
        $this->assertEquals(3, $this->image->width());
        $this->assertEquals(2, $this->image->height());
        $this->assertInstanceOf(SizeInterface::class, $this->image->size());
    }

    public function testColorspace(): void
    {
        $this->assertInstanceOf(ColorspaceInterface::class, $this->image->colorspace());
    }

    public function testResolution(): void
    {
        $this->assertInstanceOf(ResolutionInterface::class, $this->image->resolution());
    }

    public function testPickColor(): void
    {
        $this->assertInstanceOf(ColorInterface::class, $this->image->pickColor(0, 0));
        $this->assertInstanceOf(ColorInterface::class, $this->image->pickColor(0, 0, 1));
    }

    public function testPickColors(): void
    {
        $result = $this->image->pickColors(0, 0);
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEquals(2, $result->count());
    }

    public function testProfile(): void
    {
        $this->expectException(NotSupportedException::class);
        $this->image->profile();
    }

    public function testSharpen(): void
    {
        $this->assertInstanceOf(Image::class, $this->image->sharpen(12));
    }

    public function testText(): void
    {
        $this->assertInstanceOf(Image::class, $this->image->text('test', 0, 0, new Font()));
    }
}
