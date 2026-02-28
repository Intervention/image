<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Imagick;
use ImagickException;
use Intervention\Image\Drivers\Imagick\Core;
use Intervention\Image\Drivers\SpecializableDecoder;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\AlignRotationModifier;
use Intervention\Image\Modifiers\RemoveAnimationModifier;

class NativeObjectDecoder extends SpecializableDecoder implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::supports()
     */
    public function supports(mixed $input): bool
    {
        return $input instanceof Imagick;
    }

    /**
     * {@inheritdoc}
     *
     * @see DecoderInterface::decode()
     *
     * @throws InvalidArgumentException
     * @throws StateException
     * @throws DriverException
     */
    public function decode(mixed $input): ImageInterface
    {
        if (!$input instanceof Imagick) {
            throw new InvalidArgumentException('Image source must be of type Imagick');
        }

        // For some JPEG formats, the "coalesceImages()" call leads to an image
        // completely filled with background color. The logic behind this is
        // incomprehensible for me; could be an imagick bug.
        try {
            if ($input->getImageFormat() !== 'JPEG') {
                $input = $input->coalesceImages();
            }
        } catch (ImagickException $e) {
            throw new DriverException('Failed to coalesce image', previous: $e);
        }

        // turn images with colorspace 'GRAY' into 'SRGB' to avoid working on
        // grayscale colorspace images as this results images loosing color
        // information when placed into this image.
        try {
            if ($input->getImageColorspace() === Imagick::COLORSPACE_GRAY) {
                $input->setImageColorspace(Imagick::COLORSPACE_SRGB);
            }
        } catch (ImagickException $e) {
            throw new DriverException('Failed to convert image to sRGB', previous: $e);
        }

        // create image object
        $image = new Image($this->driver(), new Core($input));

        // If autoOrientation is disabled, automatic image alignment should be prevented.
        // Therefore, it is set to "undefined" here. To still be able to correct the
        // orientation manually later, we save the original value.
        if ($this->driver()->config()->autoOrientation === false) {
            $image->core()->meta()->set('originalImageOrientation', $input->getImageOrientation());
            $input->setImageOrientation(Imagick::ORIENTATION_UNDEFINED);
        }

        // discard animation depending on config
        if (!$this->driver()->config()->decodeAnimation) {
            $image->modify(new RemoveAnimationModifier());
        }

        // adjust image rotation
        if ($this->driver()->config()->autoOrientation) {
            $image->modify(new AlignRotationModifier());
        }

        // set media type on origin
        $image->origin()->setMediaType($input->getImageMimeType());

        return $image;
    }
}
