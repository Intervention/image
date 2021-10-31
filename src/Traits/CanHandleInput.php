<?php

namespace Intervention\Image\Traits;

trait CanHandleInput
{
    protected function handleInput($value)
    {
        return (new InputHandler())->handle($value);
    }
}
