<?php

namespace Intervention\Image\Drivers\Gd;

use Intervention\Image\Drivers\Abstract\AbstractInputHandler;
use Intervention\Image\Drivers\Abstract\Decoders\AbstractDecoder;

class InputHandler extends AbstractInputHandler
{
    protected function chain(): AbstractDecoder
    {
        return new Decoders\ArrayColorDecoder(
            new Decoders\FilePathImageDecoder(
                new Decoders\BinaryImageDecoder(
                    new Decoders\DataUriImageDecoder(
                        new Decoders\Base64ImageDecoder()
                    )
                )
            )
        );
    }
}
