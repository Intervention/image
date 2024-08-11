<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Decoders;

use Imagick;
use Intervention\Image\Drivers\Imagick\Core;
use Intervention\Image\Drivers\SpecializableDecoder;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Image;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\AlignRotationModifier;
use Intervention\Image\Modifiers\RemoveAnimationModifier;

class NativeObjectDecoder extends SpecializableDecoder implements SpecializedInterface
{
    protected const SUPPORTED_COLORSPACES = [
        Imagick::COLORSPACE_SRGB,
        Imagick::COLORSPACE_CMYK,
        Imagick::COLORSPACE_HSL,
        Imagick::COLORSPACE_HSB,
    ];

    public function decode(mixed $input): ImageInterface|ColorInterface
    {
        if (!is_object($input)) {
            throw new DecoderException('Unable to decode input');
        }

        if (!($input instanceof Imagick)) {
            throw new DecoderException('Unable to decode input');
        }

        // For some JPEG formats, the "coalesceImages()" call leads to an image
        // completely filled with background color. The logic behind this is
        // incomprehensible for me; could be an imagick bug.
        if ($input->getImageFormat() != 'JPEG') {
            $input = $input->coalesceImages();
        }

        // turn image into rgb if colorspace if other than CMYK, RGB, HSL or HSV.
        // this prevents working on greyscale colorspace images when loading
        // from PNG color type greyscale format.
        if (!in_array($input->getImageColorspace(), self::SUPPORTED_COLORSPACES)) {
            $input->setImageColorspace(Imagick::COLORSPACE_SRGB);
        }

        // create image object
        $image = new Image(
            $this->driver(),
            new Core($input)
        );

        // discard animation depending on config
        if (!$this->driver()->config()->decodeAnimation) {
            $image->modify(new RemoveAnimationModifier());
        }

        // adjust image rotatation
        if ($this->driver()->config()->autoOrientation) {
            $image->modify(new AlignRotationModifier());
        }

        // set media type on origin
        $image->origin()->setMediaType($input->getImageMimeType());

        return $image;
    }
}
