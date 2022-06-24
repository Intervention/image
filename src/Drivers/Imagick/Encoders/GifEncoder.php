<?php

namespace Intervention\Image\Drivers\Imagick\Encoders;

use Imagick;
use Intervention\Image\Drivers\Abstract\Encoders\AbstractEncoder;
use Intervention\Image\Drivers\Imagick\Image;
use Intervention\Image\EncodedImage;
use Intervention\Image\Exceptions\EncoderException;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class GifEncoder extends AbstractEncoder implements EncoderInterface
{
    public function encode(ImageInterface $image): EncodedImage
    {
        $format = 'gif';
        $compression = Imagick::COMPRESSION_LZW;

        if (!is_a($image, Image::class)) {
            throw new EncoderException('Image does not match the current driver.');
        }

        $imagick = $image->getImagick();
        $imagick->setFormat($format);
        $imagick->setImageFormat($format);
        $imagick->setCompression($compression);
        $imagick->setImageCompression($compression);
        $imagick = $imagick->deconstructImages();

        return new EncodedImage($imagick->getImagesBlob(), 'image/gif');
    }
}
