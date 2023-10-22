<?php

namespace Intervention\Image\Drivers\Imagick\Encoders;

use Imagick;
use Intervention\Image\Drivers\Abstract\Encoders\AbstractEncoder;
use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class JpegEncoder extends AbstractEncoder implements EncoderInterface
{
    public function __construct(int $quality)
    {
        $this->quality = $quality;
    }

    public function encode(ImageInterface $image): EncodedImage
    {
        $format = 'jpeg';
        $compression = Imagick::COMPRESSION_JPEG;

        $imagick = $image->getFrame()->getCore();
        $imagick->setImageBackgroundColor('white');
        $imagick->setBackgroundColor('white');
        $imagick->setFormat($format);
        $imagick->setImageFormat($format);
        $imagick->setCompression($compression);
        $imagick->setImageCompression($compression);
        $imagick->setCompressionQuality($this->quality);
        $imagick->setImageCompressionQuality($this->quality);

        return new EncodedImage($imagick->getImagesBlob(), 'image/jpeg');
    }
}
