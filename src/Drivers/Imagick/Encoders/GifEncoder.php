<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Encoders;

use Imagick;
use Intervention\Image\EncodedImage;
use Intervention\Image\Encoders\GifEncoder as GenericGifEncoder;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class GifEncoder extends GenericGifEncoder implements SpecializedInterface
{
    public function encode(ImageInterface $image): EncodedImage
    {
        $format = 'GIF';
        $compression = Imagick::COMPRESSION_LZW;

        $imagick = $image->core()->native();

        $imagick->setFormat($format);
        $imagick->setImageFormat($format);
        $imagick->setCompression($compression);
        $imagick->setImageCompression($compression);

        if ($this->interlaced) {
            $imagick->setInterlaceScheme(Imagick::INTERLACE_LINE);
        }

        return new EncodedImage($imagick->getImagesBlob(), 'image/gif');
    }
}
