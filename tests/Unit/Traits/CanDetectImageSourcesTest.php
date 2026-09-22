<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Traits;

use Intervention\Image\DataUri;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Resource;
use Intervention\Image\Traits\CanDetectImageSources;
use Stringable;

final class CanDetectImageSourcesTest extends BaseTestCase
{
    public function testCouldBeBase64DataWithValidBase64(): void
    {
        $this->assertTrue($this->createDetector()->callCouldBeBase64Data(Resource::create('test.jpg')->base64()));
    }

    public function testCouldBeBase64DataWithPaddedBase64(): void
    {
        $this->assertTrue($this->createDetector()->callCouldBeBase64Data('dGVzdA=='));
    }

    public function testCouldBeBase64DataWithNonString(): void
    {
        $detector = $this->createDetector();
        $this->assertFalse($detector->callCouldBeBase64Data(12345));
        $this->assertFalse($detector->callCouldBeBase64Data(null));
        $this->assertFalse($detector->callCouldBeBase64Data([]));
    }

    public function testCouldBeBase64DataWithNonPaddedBase64(): void
    {
        // "YWJj" is base64 for "abc" — no padding, passes through the final
        // base64_encode($decoded) === $input check
        $this->assertTrue($this->createDetector()->callCouldBeBase64Data('YWJj'));
    }

    public function testCouldBeBase64DataWithInvalidBase64(): void
    {
        $this->assertFalse($this->createDetector()->callCouldBeBase64Data('not base64 content!@#'));
    }

    public function testCouldBeBase64DataWithStringable(): void
    {
        $stringable = new class () implements Stringable {
            public function __toString(): string
            {
                return 'dGVzdA==';
            }
        };
        $this->assertTrue($this->createDetector()->callCouldBeBase64Data($stringable));
    }

    public function testCouldBeBinaryDataWithBinaryContent(): void
    {
        $this->assertTrue($this->createDetector()->callCouldBeBinaryData(Resource::create('test.jpg')->data()));
        $this->assertTrue($this->createDetector()->callCouldBeBinaryData("\xC3\x28")); // invalid UTF-8 sequence
    }

    public function testCouldBeBinaryDataWithPlainText(): void
    {
        $detector = $this->createDetector();
        $this->assertFalse($detector->callCouldBeBinaryData('Hello World'));
        $this->assertFalse($detector->callCouldBeBinaryData('画像.jpeg'));
        $this->assertFalse($detector->callCouldBeBinaryData('画像'));
        $this->assertFalse($detector->callCouldBeBinaryData('画像/test.jpg'));
        $this->assertFalse($detector->callCouldBeBinaryData('images/画像.jpg'));
        $this->assertFalse($detector->callCouldBeBinaryData('images/📂/test.jpg'));
        $this->assertFalse($detector->callCouldBeBinaryData("\xC3\xA4")); // ä
        $this->assertFalse($detector->callCouldBeBinaryData("\xC3\xA4.jpg"));
        $this->assertFalse($detector->callCouldBeBinaryData("Übersicht.jpg"));
    }

    public function testCouldBeBinaryDataWithNonString(): void
    {
        $detector = $this->createDetector();
        $this->assertFalse($detector->callCouldBeBinaryData(12345));
        $this->assertFalse($detector->callCouldBeBinaryData(null));
    }

    public function testCouldBeBinaryDataWithEmptyString(): void
    {
        $this->assertFalse($this->createDetector()->callCouldBeBinaryData(''));
    }

    public function testCouldBeBinaryDataWithStringable(): void
    {
        $stringable = Resource::create('test.jpg')->stringableData();
        $this->assertTrue($this->createDetector()->callCouldBeBinaryData($stringable));
    }

    public function testCouldBeDataUrlWithValidDataUrl(): void
    {
        $this->assertTrue($this->createDetector()->callCouldBeDataUrl('data:image/jpeg;base64,/9j/4AAQ'));
    }

    public function testCouldBeDataUrlWithDataUriInterface(): void
    {
        $dataUri = new DataUri('test', 'image/jpeg');
        $this->assertTrue($this->createDetector()->callCouldBeDataUrl($dataUri));
    }

    public function testCouldBeDataUrlWithNonDataUrl(): void
    {
        $detector = $this->createDetector();
        $this->assertFalse($detector->callCouldBeDataUrl('http://example.com'));
        $this->assertFalse($detector->callCouldBeDataUrl('/path/to/file.jpg'));
        $this->assertFalse($detector->callCouldBeDataUrl(12345));
    }

    public function testCouldBeFilePathWithValidPath(): void
    {
        $detector = $this->createDetector();
        $this->assertTrue($detector->callCouldBeFilePath('/path/to/file.jpg'));
        $this->assertTrue($detector->callCouldBeFilePath('relative/path/file.jpg'));
        $this->assertTrue($detector->callCouldBeFilePath('file.jpg'));
        $this->assertTrue($detector->callCouldBeFilePath('画像/image.jpg'));
        $this->assertTrue($detector->callCouldBeFilePath('images/画像.jpg'));
        $this->assertTrue($detector->callCouldBeFilePath('画像.jpg'));
        $this->assertTrue($detector->callCouldBeFilePath('画像'));
        $this->assertTrue($detector->callCouldBeFilePath('images/📂/test.jpg'));
        $this->assertTrue($detector->callCouldBeFilePath("\xC3\xA4")); // ä
        $this->assertTrue($detector->callCouldBeFilePath("\xC3\xA4.jpg"));
        $this->assertTrue($detector->callCouldBeFilePath('Übersicht.jpg'));
    }

    public function testCouldBeFilePathWithNonString(): void
    {
        $detector = $this->createDetector();
        $this->assertFalse($detector->callCouldBeFilePath(12345));
        $this->assertFalse($detector->callCouldBeFilePath(null));
        $this->assertFalse($detector->callCouldBeFilePath(''));
    }

    public function testCouldBeFilePathWithBinaryData(): void
    {
        $this->assertFalse($this->createDetector()->callCouldBeFilePath("\x00\x01\x02binary"));
        $this->assertFalse($this->createDetector()->callCouldBeFilePath(Resource::create('test.jpg')->data()));
    }

    public function testCouldBeFilePathWithTooLongPath(): void
    {
        $longPath = str_repeat('a', PHP_MAXPATHLEN + 1);
        $this->assertFalse($this->createDetector()->callCouldBeFilePath($longPath));
    }

    public function testCouldBeFilePathWithAbsolutePath(): void
    {
        $path = DIRECTORY_SEPARATOR . 'absolute' . DIRECTORY_SEPARATOR . 'path';
        $this->assertTrue($this->createDetector()->callCouldBeFilePath($path));
    }

    public function testCouldBeFilePathWithStringable(): void
    {
        $stringable = new class () implements Stringable {
            public function __toString(): string
            {
                return '/path/to/file.jpg';
            }
        };
        $this->assertTrue($this->createDetector()->callCouldBeFilePath($stringable));
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
