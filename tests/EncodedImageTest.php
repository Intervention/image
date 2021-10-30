<?php

namespace Intervention\Image\Tests;

use Intervention\Image\EncodedImage;

class EncodedImageTest extends TestCase
{
    public function testConstructor()
    {
        $image = new EncodedImage('foo', 'bar');
        $this->assertInstanceOf(EncodedImage::class, $image);
    }

    public function testSave(): void
    {
        $image = new EncodedImage('foo', 'bar');
        $path = __DIR__ . '/foo.tmp';
        $this->assertFalse(file_exists($path));
        $image->save($path);
        $this->assertTrue(file_exists($path));
        $this->assertEquals('foo', file_get_contents($path));
        unlink($path);
    }

    public function testToDataUri(): void
    {
        $image = new EncodedImage('foo', 'bar');
        $this->assertEquals('data:bar;base64,Zm9v', $image->toDataUri());
    }

    public function testToString(): void
    {
        $image = new EncodedImage('foo', 'bar');
        $this->assertEquals('foo', (string) $image);
    }
}
