<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\DecoderException;

interface InputHandlerInterface
{
    /**
     * Try to decode the given input with each decoder of the the handler chain
     *
     * @param mixed $input
     * @throws DecoderException
     * @return ImageInterface|ColorInterface
     */
    public function handle($input): ImageInterface|ColorInterface;
}
