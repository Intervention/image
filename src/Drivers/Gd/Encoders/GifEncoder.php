<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Gif\Builder as GifBuilder;
use Intervention\Image\Drivers\DriverSpecializedEncoder;
use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\ImageInterface;

class GifEncoder extends DriverSpecializedEncoder
{
    public function encode(ImageInterface $image): EncodedImage
    {
        if ($image->isAnimated()) {
            return $this->encodeAnimated($image);
        }

        $gd = $image->core()->native();
        $data = $this->getBuffered(function () use ($gd) {
            imagegif($gd);
        });

        return new EncodedImage($data, 'image/gif');
    }

    protected function encodeAnimated(ImageInterface $image): EncodedImage
    {
        $builder = GifBuilder::canvas(
            $image->width(),
            $image->height()
        );

        foreach ($image as $frame) {
            $builder->addFrame(
                (string) $this->encode($frame->toImage($image->driver())),
                $frame->delay()
            );
        }

        $builder->setLoops($image->loops());

        return new EncodedImage($builder->encode(), 'image/gif');
    }
}
