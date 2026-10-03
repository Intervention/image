<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Encoders;

use GDImage;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Rgb\Colorspace as Rgb;
use Intervention\Gif\Builder as GifBuilder;
use Intervention\Gif\Exceptions\GifException;
use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\EncodedImage;
use Intervention\Image\Encoders\GifEncoder as GenericGifEncoder;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\EncoderException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StreamException;
use Intervention\Image\Exceptions\FilesystemException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\EncodedImageInterface;
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
     * @throws ModifierException
     * @throws StreamException
     */
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        if ($image->isAnimated()) {
            return $this->encodeAnimated($image);
        }

        $backgroundColor = $image->driver()->decodeColor(
            $image->driver()->config()->backgroundColor,
        )->toColorspace(Rgb::class);

        if (!$backgroundColor instanceof RgbColor) {
            throw new ModifierException('Failed to normalize background color to rgb color space');
        }

        $gd = Cloner::cloneBlended($image->core()->native(), $backgroundColor);

        imagetruecolortopalette($gd, false, 256);

        $transparent = $this->resolveTransparentIndex($gd, $backgroundColor);
        if ($transparent !== null) {
            imagecolortransparent($gd, $transparent);
        }

        return $this->createEncodedImage(function ($stream) use ($gd): void {
            imageinterlace($gd, $this->interlaced);
            imagegif($gd, $stream);
        }, 'image/gif');
    }

    /**
     * @throws InvalidArgumentException
     * @throws EncoderException
     * @throws DriverException
     * @throws ModifierException
     */
    protected function encodeAnimated(ImageInterface $image): EncodedImageInterface
    {
        try {
            $builder = GifBuilder::canvas(
                $image->width(),
                $image->height(),
            );

            foreach ($image as $frame) {
                $builder->addFrame(
                    source: $this->encode($frame->toImage($image->driver()))->toStream(),
                    delay: $frame->delay(),
                    interlaced: $this->interlaced,
                );
            }

            $builder->setLoops($image->loops());

            return new EncodedImage($builder->encode(), 'image/gif');
        } catch (GifException | FilesystemException $e) {
            throw new EncoderException('Failed to encode image to GIF format', previous: $e);
        }
    }

    /**
     * Resolve a transparent palette index close to background color.
     *
     * This avoids relying on truecolor transparent handling differences between
     * PHP versions while preventing accidental full transparency on opaque
     * images that do not contain a background-like transparent area.
     */
    private function resolveTransparentIndex(GdImage $gd, RgbColor $backgroundColor): ?int
    {
        $index = imagecolorclosest(
            $gd,
            $backgroundColor->red()->value(),
            $backgroundColor->green()->value(),
            $backgroundColor->blue()->value(),
        );

        $color = imagecolorsforindex($gd, $index);
        $distance = abs($color['red'] - $backgroundColor->red()->value())
            + abs($color['green'] - $backgroundColor->green()->value())
            + abs($color['blue'] - $backgroundColor->blue()->value());

        return $distance <= 24 ? $index : null;
    }
}
