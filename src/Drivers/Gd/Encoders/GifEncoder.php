<?php

namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Gif\Builder as GifBuilder;
use Intervention\Image\Drivers\Abstract\Encoders\AbstractEncoder;
use Intervention\Image\Drivers\Gd\Traits\CanReduceColors;
use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;

class GifEncoder extends AbstractEncoder implements EncoderInterface
{
    use CanReduceColors;

    public function __construct(protected int $color_limit = 0)
    {
        //
    }

    public function encode(ImageInterface $image): EncodedImage
    {
        if ($image->isAnimated()) {
            return $this->encodeAnimated($image);
        }

        $gd = $this->maybeReduceColors($image->frame()->core(), $this->color_limit);
        $data = $this->getBuffered(function () use ($gd) {
            imagegif($gd);
        });

        return new EncodedImage($data, 'image/gif');
    }

    protected function encodeAnimated(ImageInterface $image): EncodedImage
    {
        $builder = GifBuilder::canvas(
            $image->width(),
            $image->height(),
            $image->loops()
        );

        foreach ($image as $frame) {
            $builder->addFrame(
                $this->encode($frame->toImage()),
                $frame->delay()
            );
        }

        return new EncodedImage($builder->encode(), 'image/gif');
    }
}
