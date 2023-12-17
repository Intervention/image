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
}
