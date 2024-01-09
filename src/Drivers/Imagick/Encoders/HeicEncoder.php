<?php

namespace Intervention\Image\Drivers\Imagick\Encoders;

use Intervention\Image\Drivers\DriverSpecializedEncoder;
use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\EncodedImageInterface;

class HeicEncoder extends DriverSpecializedEncoder
{
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        $format = 'HEIC';

        $imagick = $image->core()->native();

        $imagick->setFormat($format);
        $imagick->setImageFormat($format);
        $imagick->setCompressionQuality($this->quality);
        $imagick->setImageCompressionQuality($this->quality);

        return new EncodedImage($imagick->getImagesBlob(), 'image/heic');
    }
}
