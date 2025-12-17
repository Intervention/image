<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Format;
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

        $origin = new Origin('image/jpeg');
        $this->assertEquals('', $origin->fileExtension());
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

    public function testFormat(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Origin()->format();

        $this->assertEquals(Format::JPEG, new Origin('image/jpeg')->format());
        $this->assertEquals(Format::GIF, new Origin('image/gif')->format());
    }
}
