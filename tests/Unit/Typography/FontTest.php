<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Typography;

use Intervention\Image\Alignment;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Resource;
use Intervention\Image\Typography\Font;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Font::class)]
final class FontTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        $font = new Font('foo.ttf');
        $this->assertInstanceOf(Font::class, $font);
        $this->assertNull($font->filepath());

        $font = new Font(Resource::create('test.ttf')->path());
        $this->assertInstanceOf(Font::class, $font);
        $this->assertEquals('test.ttf', basename($font->filepath()));
    }

    public function testSetGetSize(): void
    {
        $font = new Font();
        $this->assertEquals(12, $font->size());
        $result = $font->setSize(123);
        $this->assertInstanceOf(Font::class, $result);
        $this->assertEquals(123, $font->size());
    }

    public function testSetGetAngle(): void
    {
        $font = new Font();
        $this->assertEquals(0, $font->angle());
        $result = $font->setAngle(123);
        $this->assertInstanceOf(Font::class, $result);
        $this->assertEquals(123, $font->angle());
    }

    public function testSetGetFilename(): void
    {
        $font = new Font();
        $this->assertEquals(null, $font->filepath());
        $this->assertFalse($font->hasFile());
        $filename = Resource::create()->path();
        $result = $font->setFilepath($filename);
        $this->assertTrue($font->hasFile());
        $this->assertInstanceOf(Font::class, $result);
        $this->assertEquals($filename, $font->filepath());
    }

    public function testSetGetColor(): void
    {
        $font = new Font();
        $this->assertEquals('000000', $font->color());
        $result = $font->setColor('fff');
        $this->assertInstanceOf(Font::class, $result);
        $this->assertEquals('fff', $font->color());
    }

    public function testSetGetAlignment(): void
    {
        $font = new Font();
        $this->assertEquals(Alignment::LEFT, $font->horizontalAlignment());

        $result = $font->setHorizontalAlignment(Alignment::CENTER);
        $this->assertInstanceOf(Font::class, $result);
        $this->assertEquals(Alignment::CENTER, $font->horizontalAlignment());

        $result = $font->setHorizontalAlignment(Alignment::BOTTOM);
        $this->assertInstanceOf(Font::class, $result);
        $this->assertEquals(Alignment::BOTTOM, $font->horizontalAlignment());
    }

    public function testSetGetVerticalAlignment(): void
    {
        $font = new Font();
        $this->assertEquals(Alignment::BOTTOM, $font->verticalAlignment());

        $result = $font->setVerticalAlignment(Alignment::CENTER);
        $this->assertInstanceOf(Font::class, $result);
        $this->assertEquals(Alignment::CENTER, $font->verticalAlignment());

        $result = $font->setVerticalAlignment(Alignment::RIGHT);
        $this->assertInstanceOf(Font::class, $result);
        $this->assertEquals(Alignment::RIGHT, $font->verticalAlignment());
    }

    public function testSetGetLineHeight(): void
    {
        $font = new Font();
        $this->assertEquals(1.25, $font->lineHeight());
        $result = $font->setLineHeight(3.2);
        $this->assertInstanceOf(Font::class, $result);
        $this->assertEquals(3.2, $font->lineHeight());
    }

    public function testSetGetStrokeColor(): void
    {
        $font = new Font();
        $this->assertEquals('ffffff', $font->strokeColor());
        $result = $font->setStrokeColor('000000');
        $this->assertInstanceOf(Font::class, $result);
        $this->assertEquals('000000', $font->strokeColor());
    }

    public function testSetGetStrokeWidth(): void
    {
        $font = new Font();
        $this->assertEquals(0, $font->strokeWidth());
        $result = $font->setStrokeWidth(4);
        $this->assertInstanceOf(Font::class, $result);
        $this->assertEquals(4, $font->strokeWidth());
    }

    public function testSetStrokeWidthOutOfRange(): void
    {
        $font = new Font();
        $this->expectException(InvalidArgumentException::class);
        $font->setStrokeWidth(11);
    }
}
