<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Decoders;

use Intervention\Image\Drivers\Imagick\Decoders\EncodedImageObjectDecoder;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\EncodedImage;
use Intervention\Image\Image;
use Intervention\Image\Tests\ImagickTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;

#[RequiresPhpExtension('imagick')]
#[CoversClass(EncodedImageObjectDecoder::class)]
class EncodedImageObjectDecoderTest extends ImagickTestCase
{
    protected EncodedImageObjectDecoder $decoder;

    protected function setUp(): void
    {
        $this->decoder = new EncodedImageObjectDecoder();
        $this->decoder->setDriver(new Driver());
    }

    public function testDecode(): void
    {
        $result = $this->decoder->decode(new EncodedImage($this->getTestResourceData()));
        $this->assertInstanceOf(Image::class, $result);
    }
}
