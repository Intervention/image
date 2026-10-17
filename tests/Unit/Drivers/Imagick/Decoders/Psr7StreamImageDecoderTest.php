<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Imagick\Decoders;

use Exception;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Drivers\Imagick\Decoders\Psr7StreamImageDecoder;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Image;
use Intervention\Image\Tests\ImagickTestCase;
use Intervention\Image\Tests\Resource;
use Psr\Http\Message\StreamInterface;

#[RequiresPhpExtension('imagick')]
#[CoversClass(Psr7StreamImageDecoder::class)]
class Psr7StreamImageDecoderTest extends ImagickTestCase
{
    public function testDecode(): void
    {
        $decoder = new Psr7StreamImageDecoder();
        $decoder->setDriver(new Driver());

        $result = $decoder->decode($this->psr7Stream());
        $this->assertInstanceOf(Image::class, $result);
    }

    protected function psr7Stream(): object
    {
        return new class () implements StreamInterface
        {
            public function getContents(): string
            {
                return Resource::create('test.jpg')->data();
            }

            public function close(): void
            {
                throw new Exception('Not implemented');
            }

            public function detach(): null
            {
                throw new Exception('Not implemented');
            }

            public function getSize(): ?int
            {
                throw new Exception('Not implemented');
            }

            public function tell(): int
            {
                throw new Exception('Not implemented');
            }

            public function eof(): bool
            {
                throw new Exception('Not implemented');
            }

            public function isSeekable(): bool
            {
                throw new Exception('Not implemented');
            }

            public function seek(int $offset, int $whence = SEEK_SET): void
            {
                throw new Exception('Not implemented');
            }

            public function rewind(): void
            {
                throw new Exception('Not implemented');
            }

            public function isWritable(): bool
            {
                throw new Exception('Not implemented');
            }

            public function write(string $string): int
            {
                throw new Exception('Not implemented');
            }

            public function isReadable(): bool
            {
                throw new Exception('Not implemented');
            }

            public function read(int $length): string
            {
                throw new Exception('Not implemented');
            }

            public function getMetadata(?string $key = null): null
            {
                return null;
            }

            public function __toString(): string
            {
                return $this->getContents();
            }
        };
    }
}
