<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Encoders;

use Imagick;
use ImagickPixel;
use Intervention\Image\Drivers\DriverSpecializedEncoder;
use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\ImageInterface;

/**
 * @property int $quality
 */
class WebpEncoder extends DriverSpecializedEncoder
{
    public function encode(ImageInterface $image): EncodedImage
    {
        $format = 'webp';
        $compression = Imagick::COMPRESSION_ZIP;

        $imagick = $image->core()->native();
        $imagick->setImageBackgroundColor(new ImagickPixel('transparent'));

        $imagick = $imagick->mergeImageLayers(Imagick::LAYERMETHOD_MERGE);
        $imagick->setFormat($format);
        $imagick->setImageFormat($format);
        $imagick->setCompression($compression);
        $imagick->setImageCompression($compression);
        $imagick->setImageCompressionQuality($this->quality);

        return new EncodedImage($imagick->getImagesBlob(), 'image/webp');
    }
}
