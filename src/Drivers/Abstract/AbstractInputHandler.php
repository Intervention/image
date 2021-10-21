<?php

namespace Intervention\Image\Drivers\Abstract;

use Intervention\Image\Drivers\Abstract\Decoders\AbstractDecoder;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;

abstract class AbstractInputHandler
{
    abstract protected function chain(): AbstractDecoder;

    public function handle($input): ImageInterface|ColorInterface
    {
        return $this->chain()->handle($input);
    }
}
