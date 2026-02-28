<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use Intervention\Image\Exceptions\DirectoryNotFoundException;
use Intervention\Image\Exceptions\FileNotFoundException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\File;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Resource;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(File::class)]
final class FileTest extends BaseTestCase
{
    public function testConstructor(): void
    {
        $file = new File();
        $this->assertInstanceOf(File::class, $file);

        $file = new File('foo');
        $this->assertInstanceOf(File::class, $file);
    }

    public function testConstructorFromString(): void
    {
        $file = new File('foo');
        $this->assertInstanceOf(File::class, $file);
    }

    public function testConstructorFromResource(): void
    {
        $file = new File(fopen('php://temp', 'r'));
        $this->assertInstanceOf(File::class, $file);
    }

    public function testFromPath(): void
    {
        $file = File::fromPath(Resource::create()->path());
        $this->assertInstanceOf(File::class, $file);
        $this->assertTrue($file->size() > 0);
    }

    public function testFromPathNotFound(): void
    {
        $this->expectException(FileNotFoundException::class);
        File::fromPath('/tmp/nonexistent_file_' . hrtime(true) . '.jpg');
    }

    public function testSave(): void
    {
        $file = new File('foo');
        $filenames = [
            __DIR__ . '/01_file_' . strval(hrtime(true)) . '.test',
            __DIR__ . '/02_file_' . strval(hrtime(true)) . '.test',
        ];

        foreach ($filenames as $name) {
            $file->save($name);
        }

        foreach ($filenames as $name) {
            $this->assertFileExists($name);
            $this->assertEquals('foo', file_get_contents($name));
            unlink($name);
        }
    }

    public function testSaveEmptyPath(): void
    {
        $file = new File('foo');
        $this->expectException(InvalidArgumentException::class);
        $file->save('');
    }

    public function testSaveDirectoryNotFound(): void
    {
        $file = new File('foo');
        $this->expectException(DirectoryNotFoundException::class);
        $file->save('/tmp/nonexistent_dir_' . hrtime(true) . '/test.txt');
    }

    public function testToString(): void
    {
        $file = new File('foo');
        $string = $file->toString();
        $this->assertEquals('foo', $string);
        $this->assertEquals('foo', $string);
    }

    public function testCastToString(): void
    {
        $file = new File('foo');
        $this->assertEquals('foo', (string) $file);
    }

    public function testToFilePointer(): void
    {
        $file = new File('foo');
        $fp = $file->toFilePointer();
        $this->assertIsResource($fp);
    }

    public function testSize(): void
    {
        $file = new File();
        $this->assertEquals(0, $file->size());

        $file = new File('foo');
        $this->assertEquals(3, $file->size());
    }

    public function testSavePathTooLong(): void
    {
        $file = new File('foo');
        $longPath = '/tmp/' . str_repeat('a', PHP_MAXPATHLEN + 1) . '.test';
        $this->expectException(InvalidArgumentException::class);
        $file->save($longPath);
    }

    public function testConstructorInvalidType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new File(123);
    }
}
