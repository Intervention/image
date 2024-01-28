<?php

declare(strict_types=1);

namespace Intervention\Image\Traits;

trait CanBuildFilePointer
{
    /**
     * Transform the provided data into a pointer with the data as its content
     *
     * @param string $data
     * @return resource|false
     */
    public function buildFilePointer(string $data)
    {
        $pointer = fopen('php://temp', 'rw');
        fputs($pointer, $data);
        rewind($pointer);

        return $pointer;
    }
}
