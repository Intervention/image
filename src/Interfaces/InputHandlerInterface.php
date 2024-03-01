<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\RuntimeException;

interface InputHandlerInterface
{
    /**
     * Try to decode the given input with each decoder of the the handler chain
     *
     * @param mixed $input
     * @throws RuntimeException
     * @return ImageInterface|ColorInterface
     */
    public function handle($input): ImageInterface|ColorInterface;
}
