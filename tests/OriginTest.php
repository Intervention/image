<?php

namespace Intervention\Image\Tests;

use Intervention\Image\Origin;

class OriginTest extends TestCase
{
    public function testMediaType(): void
    {
        $origin = new Origin();
        $this->assertEquals('application/octet-stream', $origin->mediaType());

        $origin = new Origin('image/gif');
        $this->assertEquals('image/gif', $origin->mediaType());
    }

    public function testFileExtension(): void
    {
        $origin = new Origin('image/jpeg', __DIR__ . '/tests/images/example.jpg');
        $this->assertEquals('jpg', $origin->fileExtension());

        $origin = new Origin('image/jpeg');
        $this->assertEquals('', $origin->fileExtension());
    }
}
