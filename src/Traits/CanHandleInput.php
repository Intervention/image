<?php

namespace Intervention\Image\Traits;

use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;

trait CanHandleInput
{
    use CanResolveDriverClass;

    public function handleInput($input): ImageInterface|ColorInterface
    {
        return $this->resolveDriverClass('InputHandler')->handle($input);
    }
}
