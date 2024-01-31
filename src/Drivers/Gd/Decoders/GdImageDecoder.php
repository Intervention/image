<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Decoders;

use GdImage;
use Intervention\Image\Drivers\Gd\Core;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Drivers\Gd\Frame;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ColorInterface;

class GdImageDecoder extends AbstractDecoder
{
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!is_object($input)) {
            throw new DecoderException('Unable to decode input');
        }

        if (!($input instanceof GdImage)) {
            throw new DecoderException('Unable to decode input');
        }

        if (!imageistruecolor($input)) {
            imagepalettetotruecolor($input);
        }

        imagesavealpha($input, true);

        // build image instance
        return new Image(
            new Driver(),
            new Core([
                new Frame($input)
            ])
        );
    }
}
