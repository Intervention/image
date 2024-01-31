<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Cmyk\Decoders;

use Intervention\Image\Colors\Cmyk\Color;
use Intervention\Image\Drivers\AbstractDecoder;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class StringColorDecoder extends AbstractDecoder implements DecoderInterface
{
    /**
     * Decode CMYK color strings
     *
     * @param mixed $input
     * @return ImageInterface|ColorInterface
     */
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!is_string($input)) {
            throw new DecoderException('Unable to decode input');
        }

        $pattern = '/^cmyk\((?P<c>[0-9\.]+%?), ?(?P<m>[0-9\.]+%?), ?(?P<y>[0-9\.]+%?), ?(?P<k>[0-9\.]+%?)\)$/i';
        if (preg_match($pattern, $input, $matches) != 1) {
            throw new DecoderException('Unable to decode input');
        }

        $values = array_map(function ($value) {
            return intval(round(floatval(trim(str_replace('%', '', $value)))));
        }, [$matches['c'], $matches['m'], $matches['y'], $matches['k']]);

        return new Color(...$values);
    }
}
