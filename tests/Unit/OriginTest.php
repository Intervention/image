<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Format;
use Intervention\Image\MediaType;
use Intervention\Image\Origin;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Resource;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Origin::class)]
final class OriginTest extends BaseTestCase
{
    public function testFilePath(): void
    {
        $origin = new Origin('image/jpeg', Resource::create('example.jpg')->path());
        $this->assertEquals(Resource::create('example.jpg')->path(), $origin->filePath());
    }

    public function testFileExtension(): void
    {
        $origin = new Origin('image/jpeg', Resource::create('example.jpg')->path());
        $this->assertEquals('jpg', $origin->fileExtension());
    }

    public function testFileExtensionNull(): void
    {
        $origin = new Origin('image/jpeg');
        $this->assertNull($origin->fileExtension());
    }

    public function testFileExtensionWithoutExtension(): void
    {
        $origin = new Origin('image/jpeg', '/path/to/file');
        $this->assertNull($origin->fileExtension());
    }

    public function testSetGetMediaType(): void
    {
        $origin = new Origin();
        $this->assertEquals('application/octet-stream', $origin->mediaType());

        $origin = new Origin('image/gif');
        $this->assertEquals('image/gif', $origin->mediaType());
        $this->assertEquals('image/gif', $origin->mimetype());
        $result = $origin->setMediaType('image/jpeg');
        $this->assertEquals('image/jpeg', $origin->mediaType());
        $this->assertEquals('image/jpeg', $result->mediaType());
    }

    public function testSetMediaTypeWithEnum(): void
    {
        $origin = new Origin();
        $result = $origin->setMediaType(MediaType::IMAGE_PNG);
        $this->assertEquals('image/png', $origin->mediaType());
        $this->assertSame($origin, $result);
    }

    public function testSetFilePath(): void
    {
        $origin = new Origin();
        $this->assertNull($origin->filePath());
        $result = $origin->setFilePath('/some/path/image.jpg');
        $this->assertEquals('/some/path/image.jpg', $origin->filePath());
        $this->assertSame($origin, $result);
    }

    public function testFormatSuccess(): void
    {
        $this->assertEquals(Format::JPEG, (new Origin('image/jpeg'))->format());
        $this->assertEquals(Format::GIF, (new Origin('image/gif'))->format());
    }

    public function testFormatFail(): void
    {
        $this->expectException(NotSupportedException::class);
        (new Origin())->format();
    }

    public function testDebugInfo(): void
    {
        $origin = new Origin('image/jpeg', '/path/to/image.jpg');
        $debugInfo = $origin->__debugInfo();
        $this->assertIsArray($debugInfo);
        $this->assertArrayHasKey('mediaType', $debugInfo);
        $this->assertArrayHasKey('filePath', $debugInfo);
        $this->assertEquals('image/jpeg', $debugInfo['mediaType']);
        $this->assertEquals('/path/to/image.jpg', $debugInfo['filePath']);
    }
}
