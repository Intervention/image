<?php

namespace Intervention\Image\Drivers\Imagick\Encoders;

use Imagick;
use Intervention\Image\Drivers\DriverSpecializedEncoder;
use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\ImageInterface;

/**
 * @property int $quality
 */
class JpegEncoder extends DriverSpecializedEncoder
{
    public function encode(ImageInterface $image): EncodedImage
    {
        $format = 'jpeg';
        $compression = Imagick::COMPRESSION_JPEG;

        $imagick = $image->core()->native();
        $imagick->setFormat($format);
        $imagick->setImageFormat($format);
        $imagick->setCompression($compression);
        $imagick->setImageCompression($compression);
        $imagick->setCompressionQuality($this->quality);
        $imagick->setImageCompressionQuality($this->quality);
        $imagick->setImageBackgroundColor('white');
        $imagick->setBackgroundColor('white');
        $imagick->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE);

        return new EncodedImage($imagick->getImagesBlob(), 'image/jpeg');
    }
}
