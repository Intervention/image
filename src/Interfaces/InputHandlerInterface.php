<?php

namespace Intervention\Image\Interfaces;

interface InputHandlerInterface
{
    /**
     * Try to decode the given input with each decoder of the the handler chain
     *
     * @param  mixed $input
     * @return ImageInterface|ColorInterface
     */
    public function handle($input): ImageInterface|ColorInterface;
}
