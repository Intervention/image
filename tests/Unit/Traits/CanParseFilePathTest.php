<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Unit\Traits;

use Intervention\Image\Exceptions\DirectoryNotFoundException;
use Intervention\Image\Exceptions\FileNotFoundException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Tests\BaseTestCase;
use Intervention\Image\Tests\Resource;
use Intervention\Image\Traits\CanParseFilePath;
use SplFileInfo;

final class CanParseFilePathTest extends BaseTestCase
{
    public function testReadableFilePathOrFailWithValidPath(): void
    {
        $parser = $this->createParser();
        $result = $parser->callReadableFilePathOrFail(Resource::create('test.jpg')->path());
        $this->assertIsString($result);
        $this->assertFileExists($result);
    }

    public function testReadableFilePathOrFailWithNonStringInput(): void
    {
        $parser = $this->createParser();
        $this->expectException(InvalidArgumentException::class);
        $parser->callReadableFilePathOrFail(12345);
    }

    public function testReadableFilePathOrFailWithEmptyString(): void
    {
        $parser = $this->createParser();
        $this->expectException(InvalidArgumentException::class);
        $parser->callReadableFilePathOrFail('');
    }

    public function testReadableFilePathOrFailWithNonExistentDirectory(): void
    {
        $parser = $this->createParser();
        $this->expectException(DirectoryNotFoundException::class);
        $parser->callReadableFilePathOrFail('/nonexistent_dir_abc123/file.jpg');
    }

    public function testReadableFilePathOrFailWithNonExistentFile(): void
    {
        $parser = $this->createParser();
        $this->expectException(FileNotFoundException::class);
        $parser->callReadableFilePathOrFail(__DIR__ . '/nonexistent_file_abc123.jpg');
    }

    public function testReadableFilePathOrFailWithDirectory(): void
    {
        $parser = $this->createParser();
        $this->expectException(FileNotFoundException::class);
        $parser->callReadableFilePathOrFail(__DIR__);
    }

    public function testReadableFilePathOrFailWithStringableInput(): void
    {
        $parser = $this->createParser();
        $stringable = Resource::create('test.jpg')->stringablePath();
        $result = $parser->callReadableFilePathOrFail($stringable);
        $this->assertIsString($result);
        $this->assertFileExists($result);
    }

    public function testFilePathFromSplFileInfoOrFailWithValidFile(): void
    {
        $parser = $this->createParser();
        $splFileInfo = new SplFileInfo(Resource::create('test.jpg')->path());
        $result = $parser->callFilePathFromSplFileInfoOrFail($splFileInfo);
        $this->assertIsString($result);
        $this->assertFileExists($result);
    }

    public function testFilePathFromSplFileInfoOrFailWithNonExistentDirectory(): void
    {
        $parser = $this->createParser();
        $splFileInfo = new SplFileInfo('/nonexistent_dir_abc123/file.jpg');
        $this->expectException(DirectoryNotFoundException::class);
        $parser->callFilePathFromSplFileInfoOrFail($splFileInfo);
    }

    public function testFilePathFromSplFileInfoOrFailWithNonExistentFile(): void
    {
        $parser = $this->createParser();
        $splFileInfo = new SplFileInfo(__DIR__ . '/nonexistent_file_abc123.jpg');
        $this->expectException(FileNotFoundException::class);
        $parser->callFilePathFromSplFileInfoOrFail($splFileInfo);
    }

    public function testFilePathFromSplFileInfoOrFailWithDirectory(): void
    {
        $parser = $this->createParser();
        $splFileInfo = new SplFileInfo(__DIR__);
        $this->expectException(FileNotFoundException::class);
        $parser->callFilePathFromSplFileInfoOrFail($splFileInfo);
    }

    /**
     * Create an anonymous class that exposes the protected trait methods.
     */
    private function createParser(): object
    {
        return new class () {
            use CanParseFilePath;

            public function callReadableFilePathOrFail(mixed $path): string
            {
                return self::readableFilePathOrFail($path);
            }

            public function callFilePathFromSplFileInfoOrFail(SplFileInfo $splFileInfo): string
            {
                return $this->filePathFromSplFileInfoOrFail($splFileInfo);
            }
        };
    }
}
