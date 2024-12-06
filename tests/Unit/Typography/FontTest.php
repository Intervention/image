<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Typography;

use Intervention\Image\Exceptions\FontException;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Typography\Font;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Font::class)]
final class FontTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        $font = new Font('foo.ttf');
        $this->assertInstanceOf(Font::class, $font);
        $this->assertEquals('foo.ttf', $font->filename());
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
        $this->assertEquals(null, $font->filename());
        $this->assertFalse($font->hasFilename());
        $filename = $this->getTestResourcePath();
        $result = $font->setFilename($filename);
        $this->assertTrue($font->hasFilename());
        $this->assertInstanceOf(Font::class, $result);
        $this->assertEquals($filename, $font->filename());
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
        $this->assertEquals('left', $font->alignment());
        $result = $font->setAlignment('center');
        $this->assertInstanceOf(Font::class, $result);
        $this->assertEquals('center', $font->alignment());
    }

    public function testSetGetValignment(): void
    {
        $font = new Font();
        $this->assertEquals('bottom', $font->valignment());
        $result = $font->setValignment('center');
        $this->assertInstanceOf(Font::class, $result);
        $this->assertEquals('center', $font->valignment());
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
        $this->expectException(FontException::class);
        $font->setStrokeWidth(11);
    }
}
