<?php

namespace Intervention\Image\Imagick;

class Encoder extends \Intervention\Image\AbstractEncoder
{
    protected function processJpeg()
    {
        $format = 'jpeg';
        $compression = \Imagick::COMPRESSION_JPEG;
        
        $imagick = $this->image->getCore();
        $imagick->setFormat($format);
        $imagick->setImageFormat($format);
        $imagick->setCompression($compression);
        $imagick->setImageCompression($compression);
        $imagick->setCompressionQuality($this->quality);
        $imagick->setImageCompressionQuality($this->quality);

        return (string) $imagick;
    }

    protected function processPng()
    {
        $format = 'png';
        $compression = \Imagick::COMPRESSION_ZIP;

        $imagick = $this->image->getCore();
        $imagick->setFormat($format);
        $imagick->setImageFormat($format);
        $imagick->setCompression($compression);
        $imagick->setImageCompression($compression);

        return (string) $imagick;
    }

    protected function processGif()
    {
        $format = 'gif';
        $compression = \Imagick::COMPRESSION_LZW;

        $imagick = $this->image->getCore();
        $imagick->setFormat($format);
        $imagick->setImageFormat($format);
        $imagick->setCompression($compression);
        $imagick->setImageCompression($compression);

        return (string) $imagick;
    }
}
