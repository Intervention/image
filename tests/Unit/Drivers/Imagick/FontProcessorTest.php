<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick;

use Intervention\Image\Drivers\Imagick\FontProcessor;
use Intervention\Image\Geometry\Point;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Typography\Font;
use Intervention\Image\Typography\TextBlock;

final class FontProcessorTest extends BaseTestCase
{
    public function testBoxSizeTtf(): void
    {
        $processor = new FontProcessor();
        $size = $processor->boxSize(
            'ABC',
            $this->testFont()->setSize(120),
        );

        $this->assertInstanceOf(SizeInterface::class, $size);
        $this->assertEquals(163, $size->width());
        $this->assertEquals(72, $size->height());
    }

    public function testNativeFontSize(): void
    {
        $processor = new FontProcessor();
        $font = new Font();
        $font->setSize(14.2);
        $size = $processor->nativeFontSize($font);
        $this->assertEquals(14.2, $size);
    }

    public function testTextBlock(): void
    {
        $processor = new FontProcessor();
        $result = $processor->textBlock(
            'test',
            $this->testFont(),
            new Point(0, 0),
        );
        $this->assertInstanceOf(TextBlock::class, $result);
    }

    public function testTypographicalSize(): void
    {
        $processor = new FontProcessor();
        $result = $processor->typographicalSize($this->testFont());
        $this->assertEquals(7, $result);
    }

    public function testCapHeight(): void
    {
        $processor = new FontProcessor();
        $result = $processor->capHeight($this->testFont());
        $this->assertEquals(7, $result);
    }

    public function testLeading(): void
    {
        $processor = new FontProcessor();
        $result = $processor->leading($this->testFont());
        $this->assertEquals(9, $result);
    }

    private function testFont(): Font
    {
        return new Font($this->getTestResourcePath('test.ttf'));
    }
}
