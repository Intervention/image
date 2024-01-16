<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;

class FilePointerImageDecoder extends BinaryImageDecoder
{
    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!is_resource($input) || !in_array(get_resource_type($input), ['file', 'stream'])) {
            throw new DecoderException('Unable to decode input');
        }

        $contents = '';
        @rewind($input);
        while (!feof($input)) {
            $contents .= fread($input, 1024);
        }

        return parent::decode($contents);
    }
}
