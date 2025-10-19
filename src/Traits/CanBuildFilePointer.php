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
     * @return resource
     */
    public function buildFilePointerOrFail(mixed $data = null)
    {
        $buildPointerStrategy = match (true) {
            is_null($data) => fn(mixed $data) => fopen('php://temp', 'r+'),
            is_resource($data) && get_resource_type($data) === 'stream' => fn(mixed $data) => $data,
            is_string($data) => function (mixed $data) {
                $pointer = fopen('php://temp', 'r+');

                if ($pointer === false) {
                    throw new RuntimeException('Unable to build file pointer');
                }

                fwrite($pointer, $data);
                return $pointer;
            },
            default => throw new RuntimeException('Unable to build file pointer'),
        };

        $pointer = call_user_func($buildPointerStrategy, $data);

        if ($pointer === false) {
            throw new RuntimeException('Unable to build file pointer');
        }

        rewind($pointer);

        return $pointer;
    }
}
