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

    public function testDecodeRemovesGrayProfileOfGrayscaleImage(): void
    {
        // A grayscale image is relabeled as sRGB. Its gray ICC profile would
        // then contradict the pixels, and an AVIF encoded from it (YUV420 plus
        // a gray profile) is refused by Chrome.
        $native = new Imagick();
        $native->newImage(3, 2, new ImagickPixel('gray50'), 'jpeg');
        $native->transformImageColorspace(Imagick::COLORSPACE_GRAY);
        $native->setImageProfile('icc', $this->profile('GRAY'));

        $result = $this->decoder->decode($native);

        $this->assertInstanceOf(RgbColorspace::class, $result->colorspace());
        $this->assertArrayNotHasKey('icc', $result->core()->native()->getImageProfiles('icc'));
    }

    public function testDecodeKeepsRgbProfile(): void
    {
        $profile = $this->profile('RGB ');
        $native = new Imagick();
        $native->newImage(3, 2, new ImagickPixel('red'), 'jpeg');
        $native->setImageProfile('icc', $profile);

        $result = $this->decoder->decode($native);

        $this->assertSame($profile, $result->core()->native()->getImageProfile('icc'));
    }

    /**
     * Build a minimal ICC v2 display profile of the given color space.
     */
    private function profile(string $colorspace): string
    {
        $description = 'Test profile';
        $d50 = pack('NNN', 0xF6D6, 0x10000, 0xD32D);

        $tags = [
            'desc' => 'desc' . "\0\0\0\0" . pack('N', strlen($description) + 1) . $description . "\0"
                . pack('NNnC', 0, 0, 0, 0) . str_repeat("\0", 67),
            'wtpt' => 'XYZ ' . "\0\0\0\0" . $d50,
            'kTRC' => 'curv' . "\0\0\0\0" . pack('Nn', 1, 0x0233),
            'cprt' => 'text' . "\0\0\0\0" . "No copyright\0",
        ];

        $offset = 128 + 4 + 12 * count($tags);
        $table = pack('N', count($tags));
        $data = '';

        foreach ($tags as $signature => $tag) {
            $table .= $signature . pack('NN', $offset + strlen($data), strlen($tag));
            $data .= str_pad($tag, (int) ceil(strlen($tag) / 4) * 4, "\0");
        }

        $header = pack('N', $offset + strlen($data)) . "\0\0\0\0" . pack('N', 0x02100000) . 'mntr' . $colorspace
            . 'XYZ ' . str_repeat("\0", 12) . 'acsp' . str_repeat("\0", 28) . $d50 . str_repeat("\0", 48);

        return $header . $table . $data;
    }
}
