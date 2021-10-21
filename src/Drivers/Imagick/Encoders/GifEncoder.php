<?php

namespace Intervention\Image\Drivers\Imagick\Encoders;

use Imagick;
use Intervention\Image\Drivers\Abstract\Encoders\AbstractEncoder;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class GifEncoder extends AbstractEncoder implements EncoderInterface
{
    public function encode(ImageInterface $image): string
    {
        $format = 'gif';
        $compression = Imagick::COMPRESSION_LZW;

        $gif = new Imagick() ;
        foreach ($image->getFrames() as $frame) {
            $gif->addImage($frame->getCore());
        }

        $gif->setImageIterations($image->getLoops());
        $gif->setFormat($format);
        $gif->setImageFormat($format);
        $gif->setCompression($compression);
        $gif->setImageCompression($compression);

        return $gif->getImagesBlob();
    }
}
