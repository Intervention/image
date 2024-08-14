<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Encoders;

use Exception;
use Intervention\Gif\Builder as GifBuilder;
use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\EncodedImage;
use Intervention\Image\Encoders\GifEncoder as GenericGifEncoder;
use Intervention\Image\Exceptions\EncoderException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class GifEncoder extends GenericGifEncoder implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     */
    public function encode(ImageInterface $image): EncodedImage
    {
        if ($image->isAnimated()) {
            return $this->encodeAnimated($image);
        }

        $gd = Cloner::clone($image->core()->native());
        $data = $this->buffered(function () use ($gd) {
            imageinterlace($gd, $this->interlaced);
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
                source: (string) $this->encode($frame->toImage($image->driver())),
                delay: $frame->delay(),
                interlaced: $this->interlaced
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
