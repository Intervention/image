<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use Intervention\Image\Exceptions\InputException;
use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\File;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Traits\CanBuildFilePointer;
use stdClass;

#[CoversClass(\Intervention\Image\File::class)]
final class FileTest extends BaseTestCase
{
    use CanBuildFilePointer;

    public function testConstructor(): void
    {
        $file = new File('foo');
        $this->assertInstanceOf(File::class, $file);

        $file = new File($this->buildFilePointer('foo'));
        $this->assertInstanceOf(File::class, $file);
    }

    public function testConstructorUnkownType(): void
    {
        $this->expectException(InputException::class);
        new File(new stdClass());
    }

    public function testSave(): void
    {
        $filename = __DIR__ . '/file_' . strval(hrtime(true)) . '.test';
        $file = new File('foo');
        $file->save($filename);
        $this->assertTrue(file_exists($filename));
        unlink($filename);
    }

    public function testToString(): void
    {
        $file = new File('foo');
        $string = $file->toString();
        $this->assertEquals('foo', $string);
        $this->assertEquals('foo', (string) $string);
    }

    public function testToFilePointer(): void
    {
        $file = new File('foo');
        $fp = $file->toFilePointer();
        $this->assertIsResource($fp);
    }

    public function testSize(): void
    {
        $file = new File('foo');
        $this->assertEquals(3, $file->size());
    }

    public function testToDataUri(): void
    {
        $image = new File('foo');
        $this->assertEquals('data:text/plain;base64,Zm9v', $image->toDataUri());
    }

    public function testMimetype(): void
    {
        $image = new File('foo');
        $this->assertEquals('text/plain', $image->mimetype());
        $this->assertEquals('text/plain', $image->mediaType());

        $image = new File($this->getTestResourceData());
        $this->assertEquals('image/jpeg', $image->mimetype());
        $this->assertEquals('image/jpeg', $image->mediaType());

        $image = new File("\x000\x001");
        $this->assertEquals('application/octet-stream', $image->mimetype());
        $this->assertEquals('application/octet-stream', $image->mediaType());

        $image = new File('');
        $this->assertEquals('application/x-empty', $image->mimetype());
        $this->assertEquals('application/x-empty', $image->mediaType());
    }
}
