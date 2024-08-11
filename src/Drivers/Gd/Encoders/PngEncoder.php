<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Encoders;

use GdImage;
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
        if ($this->indexed) {
            $output = clone $image;
            $output->reduceColors(255);

            return $output->core()->native();
        }

        return Cloner::clone($image->core()->native());
    }
}
