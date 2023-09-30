<?php

namespace Intervention\Image\Traits;

use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;

trait CanHandleInput
{
    use CanResolveDriverClass;

    public function handleInput($input, ?string $check_result_against_classname = null): ImageInterface|ColorInterface
    {
        $result = $this->resolveDriverClass('InputHandler')->handle($input);

        if (!is_null($check_result_against_classname) && get_class($result) != $check_result_against_classname) {
            throw new DecoderException('Decoded result is not an instance of ' . $check_result_against_classname);
        }

        return $result;
    }
}
