<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Decoders;

use Intervention\Image\Drivers\Gd\Decoders\AbstractDecoder;
use Intervention\Image\MediaType;
use Intervention\Image\Tests\BaseTestCase;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('gd')]
#[CoversClass(AbstractDecoder::class)]
final class AbstractDecoderTest extends BaseTestCase
{
    public function testGetMediaTypeFromFilePath(): void
    {
        $decoder = Mockery::mock(AbstractDecoder::class)->makePartial();
        $this->assertEquals(
            MediaType::IMAGE_JPEG,
            $decoder->getMediaTypeByFilePath($this->getTestResourcePath('test.jpg'))
        );
    }

    public function testGetMediaTypeFromFileBinary(): void
    {
        $decoder = Mockery::mock(AbstractDecoder::class)->makePartial();
        $this->assertEquals(
            MediaType::IMAGE_JPEG,
            $decoder->getMediaTypeByBinary($this->getTestResourceData('test.jpg')),
        );
    }
}
