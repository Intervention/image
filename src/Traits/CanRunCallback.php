<?php

namespace Intervention\Image\Traits;

trait CanRunCallback
{
    protected function runCallback(?callable $callback, object $object): object
    {
        if (is_callable($callback)) {
            $callback($object);
        }

        return $object;
    }
}
