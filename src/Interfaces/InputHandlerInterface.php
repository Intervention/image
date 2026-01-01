<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface InputHandlerInterface
{
    /**
     * Try to decode the given input with each decoder of the the handler chain.
     */
    public function handle(mixed $input): ImageInterface|ColorInterface;
}
