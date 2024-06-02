<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Decoders;

use Imagick;
use ImagickPixel;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
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
}
