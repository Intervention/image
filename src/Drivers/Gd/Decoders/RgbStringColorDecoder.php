<?php

namespace Intervention\Image\Drivers\Gd\Decoders;

use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class RgbStringColorDecoder extends ArrayColorDecoder implements DecoderInterface
{
    public function decode($input): ImageInterface|ColorInterface
    {
        if (!is_string($input)) {
            throw new DecoderException('Unable to decode input');
        }

        if (substr($input, 0, 3) !== 'rgb') {
            throw new DecoderException('Unable to decode input');
        }

        // rgb string like rgb(102, 200, 0)
        $pattern = "/^rgb ?\((?P<r>[0-9]{1,3}), ?(?P<g>[0-9]{1,3}), ?(?P<b>[0-9]{1,3})\)$/i";
        if ((bool) preg_match($pattern, $input, $matches)) {
            return parent::decode([$matches['r'], $matches['g'], $matches['b']]);
        }

        // rgba string like "rgba(200, 10, 30, 0.5)"
        $pattern = "/^rgba ?\(((?P<r>[0-9]{1,3})), ?((?P<g>[0-9]{1,3})), ?((?P<b>[0-9]{1,3})), ?(?P<a>[0-9.]{1,4})\)$/i";
        if ((bool) preg_match($pattern, $input, $matches)) {
            return parent::decode([$matches['r'], $matches['g'], $matches['b'], $matches['a']]);
        }

        throw new DecoderException('Unable to decode input');
    }
}
