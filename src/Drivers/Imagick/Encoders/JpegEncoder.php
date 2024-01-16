<?php

declare(strict_types=1);

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

        // resolve blending color because jpeg has no transparency
        $background = $this->driver()
            ->colorProcessor($image->colorspace())
            ->colorToNative($image->blendingColor());

        // set alpha value to 1 because Imagick renders
        // possible full transparent colors as black
        $background->setColorValue(Imagick::COLOR_ALPHA, 1);

        $imagick = $image->core()->native();
        $imagick->setImageBackgroundColor($background);
        $imagick->setBackgroundColor($background);
        $imagick->setFormat($format);
        $imagick->setImageFormat($format);
        $imagick->setCompression($compression);
        $imagick->setImageCompression($compression);
        $imagick->setCompressionQuality($this->quality);
        $imagick->setImageCompressionQuality($this->quality);
        $imagick->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE);

        return new EncodedImage($imagick->getImagesBlob(), 'image/jpeg');
    }
}
