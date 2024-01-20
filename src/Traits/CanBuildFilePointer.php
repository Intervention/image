<?php

declare(strict_types=1);

namespace Intervention\Image\Traits;

trait CanBuildFilePointer
{
    public function buildFilePointer(string $data)
    {
        $pointer = fopen('php://temp', 'rw');
        fputs($pointer, $data);
        rewind($pointer);

        return $pointer;
    }
}
