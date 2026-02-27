<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\EncodedImage;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Resource;

#[CoversClass(EncodedImage::class)]
final class EncodedImageTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        $image = new EncodedImage('foo');
        $this->assertInstanceOf(EncodedImage::class, $image);
    }

    public function testConstructorFromResource(): void
    {
        $fp = fopen('php://temp', 'r+');
        fwrite($fp, 'test data');
        rewind($fp);
        $image = new EncodedImage($fp, 'image/png');
        $this->assertInstanceOf(EncodedImage::class, $image);
        $this->assertEquals('image/png', $image->mediaType());
        $this->assertEquals('test data', (string) $image);
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

        $image = new EncodedImage(Resource::create()->data(), 'image/jpeg');
        $this->assertEquals('image/jpeg', $image->mediaType());
    }

    public function testMimetype(): void
    {
        $image = new EncodedImage('foo');
        $this->assertEquals('application/octet-stream', $image->mimetype());

        $image = new EncodedImage(Resource::create()->data(), 'image/jpeg');
        $this->assertEquals('image/jpeg', $image->mimetype());
    }

    public function testDebugInfo(): void
    {
        $info = (new EncodedImage('foo', 'image/png'))->__debugInfo();
        $this->assertEquals('image/png', $info['mediaType']);
        $this->assertEquals(3, $info['size']);
    }

    public function testDebugInfoWhenSizeThrows(): void
    {
        // Create an EncodedImage, then close the internal file pointer to trigger
        // the Throwable catch branch in __debugInfo
        $image = new EncodedImage('foo', 'image/png');

        // Use reflection to close the internal pointer so size() throws
        $ref = new \ReflectionClass($image);
        $prop = $ref->getProperty('pointer');
        $prop->setAccessible(true);
        $pointer = $prop->getValue($image);
        fclose($pointer);

        $info = $image->__debugInfo();
        $this->assertEquals('image/png', $info['mediaType']);
        $this->assertEquals(0, $info['size']);
    }
}
