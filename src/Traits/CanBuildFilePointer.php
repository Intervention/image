<?php

declare(strict_types=1);

namespace Intervention\Image\Traits;

use Intervention\Image\Exceptions\RuntimeException;

trait CanBuildFilePointer
{
    /**
     * Transform the provided data into a pointer with the data as its content
     *
     * @param resource|string|null $data
     * @throws RuntimeException
     * @return resource|false
     */
    public function buildFilePointer(mixed $data = null)
    {
        switch (true) {
            case is_string($data):
                $pointer = fopen('php://temp', 'r+');
                fwrite($pointer, $data);
                break;

            case is_resource($data) && get_resource_type($data) === 'stream':
                $pointer = $data;
                break;

            case is_null($data):
                $pointer = fopen('php://temp', 'r+');
                break;

            default:
                throw new RuntimeException('Unable to build file pointer.');
        }

        rewind($pointer);

        return $pointer;
    }
}
