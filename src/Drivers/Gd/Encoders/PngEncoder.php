<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Encoders;

use GdImage;
use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\EncodedImage;
use Intervention\Image\Encoders\PngEncoder as GenericPngEncoder;
use Intervention\Image\Exceptions\AnimationException;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Exceptions\RuntimeException;
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

        // encode
        $data = $this->buffered(function () use ($output) {
            imageinterlace($output, $this->interlaced);
            imagepng($output, null, -1);
        });

        return new EncodedImage($data, 'image/png');
    }

    /**
     * Prepare given image instance for PNG format output according to encoder settings
     *
     * @param ImageInterface $image
     * @throws RuntimeException
     * @throws ColorException
     * @throws AnimationException
     * @return GdImage
     */
    private function prepareOutput(ImageInterface $image): GdImage
    {
        if ($this->indexed === false) {
            return Cloner::clone($image->core()->native());
        }

        // clone output instance
        $output = Cloner::cloneEmpty($image->core()->native());

        // Decode configured blending color
        $blendingColor = $image->blendingColor();

        // allocate blending color with slighty different alpha value
        // to avoid "overwriting" pixels with the same color in the
        // original image with transprency
        $blendingIndex = imagecolorallocatealpha(
            $output,
            $blendingColor->channel(Red::class)->value(),
            $blendingColor->channel(Green::class)->value(),
            $blendingColor->channel(Blue::class)->value(),
            1,
        );

        // fill with blending color
        imagefill($output, 0, 0, $blendingIndex);

        // define blending index as transparent
        imagecolortransparent($output, $blendingIndex);

        // copy original into output
        imagecopy($output, $image->core()->native(), 0, 0, 0, 0, imagesx($output), imagesy($output));

        // reduce to indexed color palette
        imagetruecolortopalette($output, true, 255);

        return $output;
    }
}
