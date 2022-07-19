<?php

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Traits\CanReadHtmlColorNames;

class HtmlColorNameDecoder extends HexColorDecoder
{
    use CanReadHtmlColorNames;

    public function decode($input): ImageInterface|ColorInterface
    {
        if (!is_string($input)) {
            throw new DecoderException('Unable to decode input');
        }

        $hexcolor = $this->hexColorFromColorName($input);

        if (empty($hexcolor)) {
            throw new DecoderException('Unable to decode input');
        }

        return parent::decode($hexcolor);
    }
}
