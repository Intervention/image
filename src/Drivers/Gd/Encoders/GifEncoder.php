<?php

namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Image\Drivers\Abstract\Encoders\AbstractEncoder;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Gif\Builder as GifBuilder;

class GifEncoder extends AbstractEncoder implements EncoderInterface
{
    public function encode(ImageInterface $image): string
    {
        if ($image->isAnimated()) {
            return $this->encodeAnimated($image);
        }

        return $this->getBuffered(function () use ($image) {
            imagegif($image->getFrames()->first()->getCore());
        });
    }

    protected function encodeAnimated($image): string
    {
        $builder = GifBuilder::canvas($image->width(), $image->height(), 2);
        foreach ($image as $key => $frame) {
            $source = $this->encode($frame->toImage());
            $builder->addFrame($source, $frame->getDelay());
        }

        return $builder->encode();
    }
}
