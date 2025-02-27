<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use Intervention\Image\File;
use Intervention\Image\Tests\BaseTestCase;

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
        $file = File::fromPath($this->getTestResourcePath());
        $this->assertInstanceOf(File::class, $file);
        $this->assertTrue($file->size() > 0);
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
        $file = new File();
        $this->assertEquals(0, $file->size());

        $file = new File('foo');
        $this->assertEquals(3, $file->size());
    }
}
