<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Typography;

use Intervention\Image\Alignment;
use Intervention\Image\Interfaces\FontInterface;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Resource;
use Intervention\Image\Typography\Font;
use Intervention\Image\Typography\FontFactory;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(FontFactory::class)]
final class FontFactoryTest extends BaseTestCase
{
    public function testCreateWithFont(): void
    {
        $fontFile = Resource::create('test.ttf')->path();
        $factory = new FontFactory(new Font($fontFile));
        $result = $factory();
        $this->assertInstanceOf(FontInterface::class, $result);
        $this->assertEquals($fontFile, $result->filepath());
    }

    public function testCreateWithCallback(): void
    {
        $factory = new FontFactory(function (FontFactory $font): void {
            $font->filename(Resource::create('test.ttf')->path());
            $font->color('#b01735');
            $font->size(70);
            $font->align(Alignment::CENTER);
            $font->alignVertically(Alignment::TOP);
            $font->lineHeight(1.6);
            $font->angle(10);
            $font->wrap(100);
            $font->stroke('ff5500', 4);
        });

        $result = $factory();
        $this->assertInstanceOf(FontInterface::class, $result);
        $this->assertEquals(Resource::create('test.ttf')->path(), $result->filepath());
        $this->assertEquals('#b01735', $result->color());
        $this->assertEquals(70, $result->size());
        $this->assertEquals(Alignment::CENTER, $result->alignment());
        $this->assertEquals(Alignment::TOP, $result->verticalAlignment());
        $this->assertEquals(1.6, $result->lineHeight());
        $this->assertEquals(10, $result->angle());
        $this->assertEquals(100, $result->wrapWidth());
        $this->assertEquals(4, $result->strokeWidth());
        $this->assertEquals('ff5500', $result->strokeColor());
    }

    public function testBuild(): void
    {
        $font = FontFactory::build(function (FontFactory $font): void {
            $font->filename(Resource::create('test.ttf')->path());
            $font->color('#b01735');
            $font->size(70);
            $font->align(Alignment::CENTER);
            $font->alignVertically(Alignment::TOP);
            $font->lineHeight(1.6);
            $font->angle(10);
            $font->wrap(100);
            $font->stroke('ff5500', 4);
        });

        $this->assertInstanceOf(FontInterface::class, $font);
        $this->assertEquals(Resource::create('test.ttf')->path(), $font->filepath());
        $this->assertEquals('#b01735', $font->color());
        $this->assertEquals(70, $font->size());
        $this->assertEquals(Alignment::CENTER, $font->alignment());
        $this->assertEquals(Alignment::TOP, $font->verticalAlignment());
        $this->assertEquals(1.6, $font->lineHeight());
        $this->assertEquals(10, $font->angle());
        $this->assertEquals(100, $font->wrapWidth());
        $this->assertEquals(4, $font->strokeWidth());
        $this->assertEquals('ff5500', $font->strokeColor());
    }
}
