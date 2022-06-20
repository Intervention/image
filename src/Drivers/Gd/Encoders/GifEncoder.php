<?php

namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Gif\Builder as GifBuilder;
use Intervention\Image\Drivers\Abstract\Encoders\AbstractEncoder;
use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class GifEncoder extends AbstractEncoder implements EncoderInterface
{
    public function encode(ImageInterface $image): EncodedImage
    {
        if ($image->isAnimated()) {
            return $this->encodeAnimated($image);
        }

        $data = $this->getBuffered(function () use ($image) {
            imagegif($image->getFrame()->getCore());
        });

        return new EncodedImage($data, 'image/gif');
    }

    protected function encodeAnimated($image): EncodedImage
    {
        $builder = GifBuilder::canvas(
            $image->getWidth(),
            $image->getHeight(),
            $image->getLoops()
        );

        foreach ($image as $frame) {
            $source = $this->encode($frame->toImage());
            $builder->addFrame($source, $frame->getDelay());
        }

        return new EncodedImage($builder->encode(), 'image/gif');
    }
}
