<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Encoders;

use Exception;
use Intervention\Gif\Builder as GifBuilder;
use Intervention\Image\Drivers\DriverSpecializedEncoder;
use Intervention\Image\EncodedImage;
use Intervention\Image\Exceptions\EncoderException;
use Intervention\Image\Exceptions\RuntimeException;
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

    /**
     * @throws RuntimeException
     */
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

        try {
            $builder->setLoops($image->loops());
        } catch (Exception $e) {
            throw new EncoderException($e->getMessage(), $e->getCode(), $e);
        }

        return new EncodedImage($builder->encode(), 'image/gif');
    }
}
