<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Encoders;

use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Rgb\Colorspace as Rgb;
use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Encoders\JpegEncoder as GenericJpegEncoder;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\FilePointerException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class JpegEncoder extends GenericJpegEncoder implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     *
     * @throws InvalidArgumentException
     * @throws StateException
     * @throws ModifierException
     * @throws DriverException
     * @throws FilePointerException
     */
    public function encode(ImageInterface $image): EncodedImageInterface
    {
        $backgroundColor = $this->driver()->handleColorInput(
            $this->driver()->config()->backgroundColor
        )->toColorspace(Rgb::class);


        if (!$backgroundColor instanceof RgbColor) {
            throw new ModifierException('Failed to normalize background color to rgb color space');
        }

        $output = Cloner::cloneBlended(
            $image->core()->native(),
            background: $backgroundColor
        );

        return $this->createEncodedImage(function ($pointer) use ($output): void {
            imageinterlace($output, $this->progressive);
            imagejpeg($output, $pointer, $this->quality);
        }, 'image/jpeg');
    }
}
