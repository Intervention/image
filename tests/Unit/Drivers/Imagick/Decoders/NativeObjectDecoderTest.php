<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Decoders;

use Imagick;
use ImagickPixel;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Drivers\Imagick\Decoders\NativeObjectDecoder;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Image;
use Intervention\Image\Tests\BaseTestCase;

#[RequiresPhpExtension('imagick')]
#[CoversClass(NativeObjectDecoder::class)]
final class NativeObjectDecoderTest extends BaseTestCase
{
    protected NativeObjectDecoder $decoder;

    protected function setUp(): void
    {
        $this->decoder = new NativeObjectDecoder();
        $this->decoder->setDriver(new Driver());
    }

    public function testDecode(): void
    {
        $native = new Imagick();
        $native->newImage(3, 2, new ImagickPixel('red'), 'png');
        $result = $this->decoder->decode($native);

        $this->assertInstanceOf(Image::class, $result);
    }

    public function testDecodeNormalizesYcbcrColorspaceToSrgb(): void
    {
        // Older ImageMagick reports decoded AVIF/HEIF images in a YCbCr
        // colorspace and does not normalize it. The decoder must convert it to
        // sRGB, otherwise colorspace analysis and pixel reads operate on raw
        // luma/chroma values (here the color would come back roughly as
        // rgb(254, 128, 128) instead of the original).
        $native = new Imagick();
        $native->newImage(3, 2, new ImagickPixel('rgb(80, 160, 240)'), 'png');
        $native->transformImageColorspace(Imagick::COLORSPACE_YCBCR);

        $result = $this->decoder->decode($native);

        $this->assertInstanceOf(RgbColorspace::class, $result->colorspace());
        $this->assertColor(80, 160, 240, 255, $result->colorAt(0, 0), tolerance: 2);
    }
}
