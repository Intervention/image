<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Exceptions\RuntimeException;

interface DecoderInterface
{
    /**
     * Decode given input either to color or image
     *
     * @throws DecoderException|RuntimeException
     */
    public function decode(mixed $input): ImageInterface|ColorInterface;
}
