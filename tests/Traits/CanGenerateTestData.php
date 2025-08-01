<?php

declare(strict_types=1);

namespace Intervention\Image\Tests\Traits;

trait CanGenerateTestData
{
    public static function getTestResourcePath(string $filename = 'test.jpg'): string
    {
        return sprintf('%s/../resources/%s', __DIR__, $filename);
    }

    public static function getTestResourceData(string $filename = 'test.jpg'): string
    {
        return file_get_contents(self::getTestResourcePath($filename));
    }

    public static function getTestResourcePointer(string $filename = 'test.jpg'): mixed
    {
        $pointer = fopen('php://temp', 'rw');
        fwrite($pointer, self::getTestResourceData($filename));
        rewind($pointer);

        return $pointer;
    }
}
