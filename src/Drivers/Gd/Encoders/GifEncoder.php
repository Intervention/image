<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Gif\Builder as GifBuilder;
use Intervention\Gif\Exceptions\GifException;
use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\EncodedImage;
use Intervention\Image\Encoders\GifEncoder as GenericGifEncoder;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\EncoderException;
use Intervention\Image\Exceptions\FilePointerException;
use Intervention\Image\Exceptions\FilesystemException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class GifEncoder extends GenericGifEncoder implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     *
     * @throws InvalidArgumentException
     * @throws EncoderException
     * @throws DriverException
     * @throws FilePointerException
     */
    public function encode(ImageInterface $image): EncodedImage
    {
        if ($image->isAnimated()) {
            return $this->encodeAnimated($image);
        }

        $gd = Cloner::clone($image->core()->native());

        return $this->createEncodedImage(function ($pointer) use ($gd): void {
            imageinterlace($gd, $this->interlaced);
            imagegif($gd, $pointer);
        }, 'image/gif');
    }

    /**
     * @throws InvalidArgumentException
     * @throws EncoderException
     * @throws DriverException
     */
    protected function encodeAnimated(ImageInterface $image): EncodedImage
    {
        try {
            $builder = GifBuilder::canvas(
                $image->width(),
                $image->height()
            );

            foreach ($image as $frame) {
                $builder->addFrame(
                    source: $this->encode($frame->toImage($image->driver()))->toFilePointer(),
                    delay: $frame->delay(),
                    interlaced: $this->interlaced
                );
            }

            $builder->setLoops($image->loops());

            return new EncodedImage($builder->encode(), 'image/gif');
        } catch (GifException | FilesystemException $e) {
            throw new EncoderException('Failed to encode image to GIF format', previous: $e);
        }
    }
}
