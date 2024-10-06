<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\EncodedImage;
use Intervention\Image\Tests\BaseTestCase;

#[CoversClass(\Intervention\Image\EncodedImage::class)]
final class EncodedImageTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        $image = new EncodedImage('foo');
        $this->assertInstanceOf(EncodedImage::class, $image);
    }

    public function testSave(): void
    {
        $image = new EncodedImage('foo');
        $path = __DIR__ . '/foo.tmp';
        $this->assertFalse(file_exists($path));
        $image->save($path);
        $this->assertTrue(file_exists($path));
        $this->assertEquals('foo', file_get_contents($path));
        unlink($path);
    }

    public function testToDataUri(): void
    {
        $image = new EncodedImage('foo');
        $this->assertEquals('data:application/octet-stream;base64,Zm9v', $image->toDataUri());
    }

    public function testToString(): void
    {
        $image = new EncodedImage('foo');
        $this->assertEquals('foo', (string) $image);
    }

    public function testMediaType(): void
    {
        $image = new EncodedImage('foo');
        $this->assertEquals('application/octet-stream', $image->mediaType());

        $image = new EncodedImage($this->getTestResourceData(), 'image/jpeg');
        $this->assertEquals('image/jpeg', $image->mediaType());
    }

    public function testMimetype(): void
    {
        $image = new EncodedImage('foo');
        $this->assertEquals('application/octet-stream', $image->mimetype());

        $image = new EncodedImage($this->getTestResourceData(), 'image/jpeg');
        $this->assertEquals('image/jpeg', $image->mimetype());
    }
}
