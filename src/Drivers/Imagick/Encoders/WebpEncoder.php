<?php

namespace Intervention\Image\Drivers\Imagick\Encoders;

use Imagick;
use ImagickPixel;
use Intervention\Image\Drivers\Abstract\Encoders\AbstractEncoder;
use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class WebpEncoder extends AbstractEncoder implements EncoderInterface
{
    public function __construct(int $quality)
    {
        $this->quality = $quality;
    }

    public function encode(ImageInterface $image): EncodedImage
    {
        $format = 'webp';
        $compression = Imagick::COMPRESSION_ZIP;

        $imagick = $image->getFrame()->getCore();
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
