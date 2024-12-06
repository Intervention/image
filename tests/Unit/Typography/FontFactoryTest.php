<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Typography;

use Intervention\Image\Interfaces\FontInterface;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Typography\Font;
use Intervention\Image\Typography\FontFactory;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(FontFactory::class)]
final class FontFactoryTest extends BaseTestCase
{
    public function testBuildWithFont(): void
    {
        $font_file = $this->getTestResourcePath('test.ttf');
        $factory = new FontFactory(new Font($font_file));
        $result = $factory();
        $this->assertInstanceOf(FontInterface::class, $result);
        $this->assertEquals($font_file, $result->filename());
    }

    public function testBuildWithCallback(): void
    {
        $factory = new FontFactory(function (FontFactory $font) {
            $font->filename($this->getTestResourcePath('test.ttf'));
            $font->color('#b01735');
            $font->size(70);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
            $font->angle(10);
            $font->wrap(100);
            $font->stroke('ff5500', 4);
        });

        $result = $factory();
        $this->assertInstanceOf(FontInterface::class, $result);
        $this->assertEquals($this->getTestResourcePath('test.ttf'), $result->filename());
        $this->assertEquals('#b01735', $result->color());
        $this->assertEquals(70, $result->size());
        $this->assertEquals('center', $result->alignment());
        $this->assertEquals('middle', $result->valignment());
        $this->assertEquals(1.6, $result->lineHeight());
        $this->assertEquals(10, $result->angle());
        $this->assertEquals(100, $result->wrapWidth());
        $this->assertEquals(4, $result->strokeWidth());
        $this->assertEquals('ff5500', $result->strokeColor());
    }
}
