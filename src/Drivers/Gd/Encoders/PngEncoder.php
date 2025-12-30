<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Encoders;

use GdImage;
use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\EncodedImage;
use Intervention\Image\Encoders\PngEncoder as GenericPngEncoder;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\FilePointerException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

class PngEncoder extends GenericPngEncoder implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see EncoderInterface::encode()
     *
     * @throws InvalidArgumentException
     * @throws FilePointerException
     * @throws DriverException
     */
    public function encode(ImageInterface $image): EncodedImage
    {
        $output = $this->prepareOutput($image);

        return $this->createEncodedImage(function ($pointer) use ($output): void {
            imageinterlace($output, $this->interlaced);
            imagepng($output, $pointer, -1);
        }, 'image/png');
    }

    /**
     * Prepare given image instance for PNG format output according to encoder settings
     *
     * @throws InvalidArgumentException
     * @throws DriverException
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
