<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use ImagickPixel;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\CropModifier as GenericCropModifier;

class CropModifier extends GenericCropModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        // decode background color
        $background = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->driver()->handleInput($this->background)
        );

        // create empty container imagick to rebuild core
        $imagick = new Imagick();

        // save resolution to add it later
        $resolution = $image->resolution()->perInch();

        // define position of the image on the new canvas
        $crop = $this->crop($image);
        $position = [
            ($crop->pivot()->x() + $this->offset_x) * -1,
            ($crop->pivot()->y() + $this->offset_y) * -1,
        ];

        foreach ($image as $frame) {
            // create new frame canvas with modifiers background
            $canvas = new Imagick();
            $canvas->newImage($crop->width(), $crop->height(), $background, 'png');
            $canvas->setImageResolution($resolution->x(), $resolution->y());
            $canvas->setImageAlphaChannel(Imagick::ALPHACHANNEL_SET); // or ALPHACHANNEL_ACTIVATE?

            // set animation details
            if ($image->isAnimated()) {
                $canvas->setImageDelay($frame->native()->getImageDelay());
                $canvas->setImageIterations($frame->native()->getImageIterations());
                $canvas->setImageDispose($frame->native()->getImageDispose());
            }

            // make the rectangular position of the original image transparent
            // so that we can later place the original on top. this preserves
            // the transparency of the original and shows the background color
            // of the modifier in the other areas. if the original image has no
            // transparent area the rectangular transparency will be covered by
            // the original.
            $clearer = new Imagick();
            $clearer->newImage(
                $frame->native()->getImageWidth(),
                $frame->native()->getImageHeight(),
                new ImagickPixel('black'),
            );
            $canvas->compositeImage($clearer, Imagick::COMPOSITE_DSTOUT, ...$position);

            // place original frame content onto prepared frame canvas
            $canvas->compositeImage($frame->native(), Imagick::COMPOSITE_DEFAULT, ...$position);

            // add newly built frame to container imagick
            $imagick->addImage($canvas);
        }

        // replace imagick in the original image
        $image->core()->setNative($imagick);

        return $image;
    }
}
