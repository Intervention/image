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
        $detector = $this->createDetector();
        $this->assertTrue($detector->callCouldBeBase64Data(Resource::create('test.jpg')->base64()));
    }

    public function testCouldBeBase64DataWithPaddedBase64(): void
    {
        $detector = $this->createDetector();
        $this->assertTrue($detector->callCouldBeBase64Data('dGVzdA=='));
    }

    public function testCouldBeBase64DataWithNonString(): void
    {
        $detector = $this->createDetector();
        $this->assertFalse($detector->callCouldBeBase64Data(12345));
        $this->assertFalse($detector->callCouldBeBase64Data(null));
        $this->assertFalse($detector->callCouldBeBase64Data([]));
    }

    public function testCouldBeBase64DataWithInvalidBase64(): void
    {
        $detector = $this->createDetector();
        $this->assertFalse($detector->callCouldBeBase64Data('not base64 content!@#'));
    }

    public function testCouldBeBase64DataWithStringable(): void
    {
        $detector = $this->createDetector();
        $stringable = new class () implements Stringable {
            public function __toString(): string
            {
                return 'dGVzdA==';
            }
        };
        $this->assertTrue($detector->callCouldBeBase64Data($stringable));
    }

    public function testCouldBeBinaryDataWithBinaryContent(): void
    {
        $detector = $this->createDetector();
        $this->assertTrue($detector->callCouldBeBinaryData(Resource::create('test.jpg')->data()));
    }

    public function testCouldBeBinaryDataWithPlainText(): void
    {
        $detector = $this->createDetector();
        $this->assertFalse($detector->callCouldBeBinaryData('Hello World'));
    }

    public function testCouldBeBinaryDataWithNonString(): void
    {
        $detector = $this->createDetector();
        $this->assertFalse($detector->callCouldBeBinaryData(12345));
        $this->assertFalse($detector->callCouldBeBinaryData(null));
    }

    public function testCouldBeBinaryDataWithEmptyString(): void
    {
        $detector = $this->createDetector();
        $this->assertTrue($detector->callCouldBeBinaryData(''));
    }

    public function testCouldBeBinaryDataWithStringable(): void
    {
        $detector = $this->createDetector();
        $stringable = Resource::create('test.jpg')->stringableData();
        $this->assertTrue($detector->callCouldBeBinaryData($stringable));
    }

    public function testCouldBeDataUrlWithValidDataUrl(): void
    {
        $detector = $this->createDetector();
        $this->assertTrue($detector->callCouldBeDataUrl('data:image/jpeg;base64,/9j/4AAQ'));
    }

    public function testCouldBeDataUrlWithDataUriInterface(): void
    {
        $detector = $this->createDetector();
        $dataUri = new DataUri('test', 'image/jpeg');
        $this->assertTrue($detector->callCouldBeDataUrl($dataUri));
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
    }

    public function testCouldBeFilePathWithNonString(): void
    {
        $detector = $this->createDetector();
        $this->assertFalse($detector->callCouldBeFilePath(12345));
        $this->assertFalse($detector->callCouldBeFilePath(null));
    }

    public function testCouldBeFilePathWithBinaryData(): void
    {
        $detector = $this->createDetector();
        $this->assertFalse($detector->callCouldBeFilePath("\x00\x01\x02binary"));
    }

    public function testCouldBeFilePathWithTooLongPath(): void
    {
        $detector = $this->createDetector();
        $longPath = str_repeat('a', PHP_MAXPATHLEN + 1);
        $this->assertFalse($detector->callCouldBeFilePath($longPath));
    }

    public function testCouldBeFilePathWithAbsolutePath(): void
    {
        $detector = $this->createDetector();
        $path = DIRECTORY_SEPARATOR . 'absolute' . DIRECTORY_SEPARATOR . 'path';
        $this->assertTrue($detector->callCouldBeFilePath($path));
    }

    public function testCouldBeFilePathWithStringable(): void
    {
        $detector = $this->createDetector();
        $stringable = new class () implements Stringable {
            public function __toString(): string
            {
                return '/path/to/file.jpg';
            }
        };
        $this->assertTrue($detector->callCouldBeFilePath($stringable));
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
