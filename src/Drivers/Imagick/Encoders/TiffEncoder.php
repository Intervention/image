<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Encoders;

use Intervention\Image\Drivers\DriverSpecializedEncoder;
use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\EncodedImageInterface;

/**
 * @property int $quality
 */
class TiffEncoder extends DriverSpecializedEncoder
{
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        $format = 'TIFF';

        $imagick = $image->core()->native();
        $imagick->setFormat($format);
        $imagick->setImageFormat($format);
        $imagick->setCompression($imagick->getImageCompression());
        $imagick->setImageCompression($imagick->getImageCompression());
        $imagick->setCompressionQuality($this->quality);
        $imagick->setImageCompressionQuality($this->quality);

        return new EncodedImage($imagick->getImagesBlob(), 'image/tiff');
    }
}
