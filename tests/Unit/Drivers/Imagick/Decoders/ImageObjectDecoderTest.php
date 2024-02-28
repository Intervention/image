<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Decoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Drivers\Imagick\Decoders\ImageObjectDecoder;
use Intervention\Image\Image;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

#[RequiresPhpExtension('imagick')]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Decoders\ImageObjectDecoder::class)]
final class ImageObjectDecoderTest extends BaseTestCase
{
    use CanCreateImagickTestImage;

    public function testDecode(): void
    {
        $decoder = new ImageObjectDecoder();
        $result = $decoder->decode($this->readTestImage('blue.gif'));
        $this->assertInstanceOf(Image::class, $result);
    }
}
