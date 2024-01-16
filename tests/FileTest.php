<?php

declare(strict_types=1);

namespace Intervention\Image\Tests;

use Intervention\Image\File;

/**
 * @covers \Intervention\Image\File
 */
class FileTest extends TestCase
{
    public function testConstructor(): void
    {
        $file = new File('foo');
        $this->assertInstanceOf(File::class, $file);
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
}
