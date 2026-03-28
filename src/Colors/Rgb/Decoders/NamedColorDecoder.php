<?php

declare(strict_types=1);

namespace Intervention\Image\Colors\Rgb\Decoders;

use Intervention\Image\Colors\Rgb\NamedColor;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;

class NamedColorDecoder extends HexColorDecoder implements DecoderInterface
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::supports()
     */
    public function supports(mixed $input): bool
    {
        if (!is_string($input)) {
            return false;
        }

        return $this->stringToColorName($input) === null ? false : true;
    }

    /**
     * Decode html color names.
     */
    public function decode(mixed $input): ColorInterface
    {
        return parent::decode($this->stringToColorName($input)?->toHex());
    }

    private function stringToColorName(string $input): ?NamedColor
    {
        return NamedColor::tryFrom(strtolower($input));
    }
}
