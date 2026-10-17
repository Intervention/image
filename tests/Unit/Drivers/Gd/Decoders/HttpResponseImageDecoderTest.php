<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Drivers\Gd\Decoders;

use Exception;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Intervention\Image\Drivers\Gd\Decoders\HttpResponseImageDecoder;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Image;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

#[RequiresPhpExtension('gd')]
#[CoversClass(HttpResponseImageDecoder::class)]
final class HttpResponseImageDecoderTest extends Psr7StreamImageDecoderTest
{
    public function testDecode(): void
    {
        $decoder = new HttpResponseImageDecoder();
        $decoder->setDriver(new Driver());

        $result = $decoder->decode($this->psr7HttpResponse());
        $this->assertInstanceOf(Image::class, $result);
    }

    private function psr7HttpResponse(): object
    {
        return new class ($this->psr7Stream()) implements ResponseInterface
        {
            public function __construct(private object $stream)
            {
                //
            }

            public function getStatusCode(): int
            {
                throw new Exception('Not implemented');
            }

            public function withStatus(int $code, string $reasonPhrase = ''): ResponseInterface
            {
                throw new Exception('Not implemented');
            }

            public function getReasonPhrase(): string
            {
                throw new Exception('Not implemented');
            }

            public function getProtocolVersion(): string
            {
                throw new Exception('Not implemented');
            }

            public function withProtocolVersion(string $version): MessageInterface
            {
                throw new Exception('Not implemented');
            }

            /**
             * @return array<mixed>
             */
            public function getHeaders(): array
            {
                throw new Exception('Not implemented');
            }

            public function hasHeader(string $name): bool
            {
                throw new Exception('Not implemented');
            }

            /**
             * @return array<mixed>
             */
            public function getHeader(string $name): array
            {
                throw new Exception('Not implemented');
            }

            public function getHeaderLine(string $name): string
            {
                throw new Exception('Not implemented');
            }

            public function withHeader(string $name, mixed $value): MessageInterface
            {
                throw new Exception('Not implemented');
            }

            public function withAddedHeader(string $name, mixed $value): MessageInterface
            {
                throw new Exception('Not implemented');
            }

            public function withoutHeader(string $name): MessageInterface
            {
                throw new Exception('Not implemented');
            }

            public function getBody(): StreamInterface
            {
                return $this->stream;
            }

            public function withBody(StreamInterface $body): MessageInterface
            {
                throw new Exception('Not implemented');
            }
        };
    }
}
