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
        $file = new File();
        $this->assertEquals(0, $file->size());

        $file = new File('foo');
        $this->assertEquals(3, $file->size());
    }
}
