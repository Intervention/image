<?php

namespace Intervention\Image\Traits;

trait CanRunCallback
{
    protected function runCallback(callable $callback, object $object): object
    {
        $callback($object);

        return $object;
    }

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
            return $this->runCallback($callback, $object);
        }

        return $object;
    }
}
