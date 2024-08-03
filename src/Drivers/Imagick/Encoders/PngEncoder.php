<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Encoders;

use Imagick;
use ImagickException;
use Intervention\Image\EncodedImage;
use Intervention\Image\Encoders\PngEncoder as GenericPngEncoder;
use Intervention\Image\Exceptions\AnimationException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class PngEncoder extends GenericPngEncoder implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     */
    public function encode(ImageInterface $image): EncodedImage
    {
        $output = $this->prepareOutput($image);

        $output->setCompression(Imagick::COMPRESSION_ZIP);
        $output->setImageCompression(Imagick::COMPRESSION_ZIP);

        if ($this->interlaced) {
            $output->setInterlaceScheme(Imagick::INTERLACE_LINE);
        }

        return new EncodedImage($output->getImagesBlob(), 'image/png');
    }

    /**
     * Prepare given image instance for PNG format output according to encoder settings
     *
     * @param ImageInterface $image
     * @throws AnimationException
     * @throws RuntimeException
     * @throws ColorException
     * @throws ImagickException
     * @return Imagick
     */
    private function prepareOutput(ImageInterface $image): Imagick
    {
        if ($this->indexed === false) {
            $output = clone $image->core()->native();

            // ensure to encode PNG image type 6 true color alpha
            $output->setFormat('PNG32');
            $output->setImageFormat('PNG32');

            return $output;
        }

        // get blending color
        $blendingColor =  $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->driver()->handleInput($this->driver()->config()->blendingColor)
        );

        // create new image with blending color as background
        $output = new Imagick();
        $output->newImage($image->width(), $image->height(), $blendingColor, 'PNG');

        // set transparency of original image
        $output->compositeImage($image->core()->native(), Imagick::COMPOSITE_DSTIN, 0, 0);
        $output->transparentPaintImage('#000000', 0, 0, false);

        // copy original and create indexed color palette version
        $output->compositeImage($image->core()->native(), Imagick::COMPOSITE_DEFAULT, 0, 0);
        $output->quantizeImage(255, $output->getImageColorSpace(), 0, false, false);

        // ensure to encode PNG image type 3 (indexed)
        $output->setFormat('PNG8');
        $output->setImageFormat('PNG8');

        return $output;
    }
}
