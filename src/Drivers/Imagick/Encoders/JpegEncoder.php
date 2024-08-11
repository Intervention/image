<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Encoders;

use Imagick;
use Intervention\Image\EncodedImage;
use Intervention\Image\Encoders\JpegEncoder as GenericJpegEncoder;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class JpegEncoder extends GenericJpegEncoder implements SpecializedInterface
{
    public function encode(ImageInterface $image): EncodedImage
    {
        $format = 'JPEG';
        $compression = Imagick::COMPRESSION_JPEG;
        $blendingColor = $this->driver()->handleInput(
            $this->driver()->config()->blendingColor
        );

        // resolve blending color because jpeg has no transparency
        $background = $this->driver()
            ->colorProcessor($image->colorspace())
            ->colorToNative($blendingColor);

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

        if ($this->progressive) {
            $imagick->setInterlaceScheme(Imagick::INTERLACE_PLANE);
        }

        return new EncodedImage($imagick->getImagesBlob(), 'image/jpeg');
    }
}
