<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Traits;

use Generator;
use Intervention\Image\DataUri;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Resource;
use Intervention\Image\Traits\CanDetectImageSources;
use PHPUnit\Framework\Attributes\DataProvider;
use Stringable;

final class CanDetectImageSourcesTest extends BaseTestCase
{
    protected object $detector;

    protected function setUp(): void
    {
        $this->detector = $this->createDetector();
    }

    #[DataProvider('provideBase64Input')]
    public function testCouldBeBase64(mixed $input, bool $result): void
    {
        $this->assertEquals($result, $this->detector->callCouldBeBase64Data($input));
    }

    public static function provideBase64Input(): Generator
    {
        yield [Resource::create('test.jpg')->base64(), true];
        yield ['dGVzdA==', true];
        yield ['YWJj', true]; // "YWJj" is base64 for "abc" — no padding
        yield [
            new class () implements Stringable {
                public function __toString(): string
                {
                    return 'dGVzdA==';
                }
            },
            true,
        ];
        yield [12345, false];
        yield [null, false];
        yield [[], false];
        yield ['not base64 content!@#', false];
        yield ['images/test.jpg', false];
    }

    #[DataProvider('provideBinaryInput')]
    public function testCouldBeBinary(mixed $input, bool $result): void
    {
        $this->assertEquals($result, $this->detector->callCouldBeBinaryData($input));
    }

    public static function provideBinaryInput(): Generator
    {
        yield [Resource::create('test.jpg')->data(), true];
        yield [Resource::create('test.svg')->data(), true];
        yield [Resource::create('test.jpg')->stringableData(), true];
        yield ["\xC3\x28", true]; // invalid UTF-8 sequence
        yield ['Hello World', false];
        yield ['画像.jpeg', false];
        yield ['画像', false];
        yield ['画像/test.jpg', false];
        yield ['images/画像.jpg', false];
        yield ['images/📂/test.jpg', false];
        yield ["\xC3\xA4", false]; // ä
        yield ["\xC3\xA4.jpg", false];
        yield ["Übersicht.jpg", false];
        yield [12345, false];
        yield [null, false];
        yield ['', false];
    }

    #[DataProvider('provideDataUrlInput')]
    public function testCouldBeDataUrl(mixed $input, bool $result): void
    {
        $this->assertEquals($result, $this->detector->callCouldBeDataUrl($input));
    }

    public static function provideDataUrlInput(): Generator
    {
        yield ['data:image/jpeg;base64,/9j/4AAQ', true];
        yield [new DataUri('test', 'image/jpeg'), true];
        yield ['http://example.com', false];
        yield ['/path/to/file.jpg', false];
        yield [12345, false];
    }

    #[DataProvider('provideFilePathInput')]
    public function testCouldBeFilePathValid(mixed $input, bool $result): void
    {
        $this->assertEquals($result, $this->detector->callCouldBeFilePath($input));
    }

    public static function provideFilePathInput(): Generator
    {
        yield ['/path/to/file.jpg', true];
        yield ['relative/path/file.jpg', true];
        yield ['file.jpg', true];
        yield ['画像/image.jpg', true];
        yield ['images/画像.jpg', true];
        yield ['画像.jpg', true];
        yield ['画像', true];
        yield ['images/📂/test.jpg', true];
        yield ["\xC3\xA4", true]; // ä
        yield ["\xC3\xA4.jpg", true];
        yield ['Übersicht.jpg', true];
        yield [DIRECTORY_SEPARATOR . 'absolute' . DIRECTORY_SEPARATOR . 'path', true];
        yield [
            new class () implements Stringable {
                public function __toString(): string
                {
                    return '/path/to/file.jpg';
                }
            },
            true,
        ];

        yield [12345, false];
        yield [null, false];
        yield ['', false];
        yield ["\x00\x01\x02binary", false];
        yield [Resource::create('test.jpg')->data(), false];
        yield [Resource::create('test.svg')->data(), false];
        yield ["\0test", false];
        yield [str_repeat('a', PHP_MAXPATHLEN + 1), false];
    }

    /**
     * Create an anonymous class that exposes the protected trait methods.
     */
    private function createDetector(): object
    {
        return new class () {
            use CanDetectImageSources;

            public function callCouldBeBase64Data(mixed $input): bool
            {
                return $this->couldBeBase64Data($input);
            }

            public function callCouldBeBinaryData(mixed $input): bool
            {
                return $this->couldBeBinaryData($input);
            }

            public function callCouldBeDataUrl(mixed $input): bool
            {
                return $this->couldBeDataUrl($input);
            }

            public function callCouldBeFilePath(mixed $input): bool
            {
                return $this->couldBeFilePath($input);
            }
        };
    }
}
