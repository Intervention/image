<?php

namespace Intervention\Image\Traits;

trait CanRunCallback
{
    /**
     * Runs given callback against given object and returns object
     *
     * @param null|callable $callback
     * @param object $object
     * @return object
     */
    protected function maybeRunCallback(?callable $callback, object $object): object
    {
        if (is_callable($callback)) {
            $callback($object);
        }

        return $object;
    }
}
