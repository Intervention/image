<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use Intervention\Image\Origin;
use Intervention\Image\Tests\BaseTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Origin::class)]
final class OriginTest extends BaseTestCase
{
    public function testFilePath(): void
    {
        $origin = new Origin('image/jpeg', $this->getTestResourcePath('example.jpg'));
        $this->assertEquals($this->getTestResourcePath('example.jpg'), $origin->filePath());
    }

    public function testFileExtension(): void
    {
        $origin = new Origin('image/jpeg', $this->getTestResourcePath('example.jpg'));
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
}
