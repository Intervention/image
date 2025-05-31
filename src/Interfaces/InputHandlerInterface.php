<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\RuntimeException;

interface InputHandlerInterface
{
    /**
     * Try to decode the given input with each decoder of the the handler chain
     *
     * @throws RuntimeException
     */
    public function handle(mixed $input): ImageInterface|ColorInterface;
}
