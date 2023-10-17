<?php

namespace Intervention\Image\Traits;

use Intervention\Image\Exceptions\TypeException;

trait CanCheckType
{
    public function failIfNotClass(mixed $input, string $classname)
    {
        if (!is_object($input) || get_class($input) != $classname) {
            throw new TypeException('Given input is not instance of ' . $classname);
        }

        return $input;
    }

    public function failIfNotInstance(mixed $input, string $classname)
    {
        if (!is_object($input) || !is_a($input, $classname)) {
            throw new TypeException('Given input is not instance of ' . $classname);
        }

        return $input;
    }
}
