<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Gd\Decoders;

use Intervention\Image\Drivers\Gd\Decoders\AbstractDecoder;
use Intervention\Image\Tests\TestCase;
use Mockery;

final class AbstractDecoderTest extends TestCase
{
    public function testGetMediaTypeFromFilePath(): void
    {
        $decoder = Mockery::mock(AbstractDecoder::class)->makePartial();
        $this->assertEquals('image/jpeg', $decoder->getMediaTypeByFilePath($this->getTestImagePath('test.jpg')));
    }

    public function testGetMediaTypeFromFileBinary(): void
    {
        $decoder = Mockery::mock(AbstractDecoder::class)->makePartial();
        $this->assertEquals('image/jpeg', $decoder->getMediaTypeByBinary($this->getTestImageData('test.jpg')));
    }
}
