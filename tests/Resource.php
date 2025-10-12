<?php

declare(strict_types=1);

namespace Intervention\Image\Tests;

use Intervention\Image\DataUri;
use Intervention\Image\Decoders\FilePathImageDecoder;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\DriverInterface;
use SplFileInfo;
use Stringable;

class Resource
{
    public function __construct(protected string $filename = 'test.jpg')
    {
        //
    }

    public static function create(string $filename = 'test.jpg'): self
    {
        return new static($filename);
    }

    public function path(): string
    {
        return sprintf('%s/resources/%s', __DIR__, $this->filename);
    }

    public function stringablePath(): Stringable
    {
        $path = $this->path();

        return new class ($path) implements Stringable
        {
            public function __construct(private string $path)
            {
                //
            }

            public function __toString(): string
            {
                return $this->path;
            }
        };
    }

    public function data(): string
    {
        return file_get_contents($this->path());
    }

    public function base64(): string
    {
        return base64_encode($this->data());
    }

    public function dataUri(): string
    {
        return DataUri::create($this->data())->toString();
    }

    public function splFileInfo(): SplFileInfo
    {
        return new SplFileInfo($this->path());
    }

    public function stringableData(): Stringable
    {
        $data = $this->data();

        return new class ($data) implements Stringable
        {
            public function __construct(private string $data)
            {
                //
            }

            public function __toString(): string
            {
                return $this->data;
            }
        };
    }

    public function pointer(): mixed
    {
        $pointer = fopen('php://temp', 'rw');
        fwrite($pointer, $this->data());
        rewind($pointer);

        return $pointer;
    }

    public function imageObject(string|DriverInterface $driver): ImageInterface
    {
        return (is_string($driver) ? new $driver() : $driver)
            ->specializeDecoder(new FilePathImageDecoder())
            ->decode($this->path());
    }
}
