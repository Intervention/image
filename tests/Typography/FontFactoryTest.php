<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Typography;

use Intervention\Image\Interfaces\FontInterface;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Typography\Font;
use Intervention\Image\Typography\FontFactory;

class FontFactoryTest extends TestCase
{
    public function testBuildWithFont(): void
    {
        $factory = new FontFactory(new Font('foo.ttf'));
        $result = $factory();
        $this->assertInstanceOf(FontInterface::class, $result);
        $this->assertEquals('foo.ttf', $result->filename());
    }

    public function testBuildWithCallback(): void
    {
        $factory = new FontFactory(function ($font) {
            $font->filename('foo.ttf');
            $font->file('bar.ttf');
            $font->color('#b01735');
            $font->size(70);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
            $font->angle(10);
        });

        $result = $factory();
        $this->assertInstanceOf(FontInterface::class, $result);
        $this->assertEquals('bar.ttf', $result->filename());
        $this->assertEquals('#b01735', $result->color());
        $this->assertEquals(70, $result->size());
        $this->assertEquals('center', $result->alignment());
        $this->assertEquals('middle', $result->valignment());
        $this->assertEquals(1.6, $result->lineHeight());
        $this->assertEquals(10, $result->angle());
    }
}
