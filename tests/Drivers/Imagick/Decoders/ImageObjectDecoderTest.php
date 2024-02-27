<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Drivers\Imagick\Decoders;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Requires;
use Intervention\Image\Drivers\Imagick\Decoders\ImageObjectDecoder;
use Intervention\Image\Image;
use Intervention\Image\Tests\TestCase;
use Intervention\Image\Tests\Traits\CanCreateImagickTestImage;

#[Requires('extension imagick')]
#[CoversClass(\Intervention\Image\Drivers\Imagick\Decoders\ImageObjectDecoder::class)]
final class ImageObjectDecoderTest extends TestCase
{
    use CanCreateImagickTestImage;

    public function testDecode(): void
    {
        $decoder = new ImageObjectDecoder();
        $result = $decoder->decode($this->readTestImage('blue.gif'));
        $this->assertInstanceOf(Image::class, $result);
    }
}
