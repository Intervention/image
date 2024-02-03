<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Imagick;

use Intervention\Image\Drivers\Imagick\FontProcessor;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Typography\Font;

class FontProcessorTest extends TestCase
{
    public function testNativeFontSize(): void
    {
        $processor = new FontProcessor();
        $font = new Font();
        $font->setSize(14.2);
        $size = $processor->nativeFontSize($font);
        $this->assertEquals(14.2, $size);
    }
}
