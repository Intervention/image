<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use Imagick;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\CropModifier as GenericCropModifier;

class CropModifier extends GenericCropModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $crop = $this->crop($image);
        $background = $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->driver()->handleInput($this->background)
        );

        // create empty container imagick to rebuild core
        $imagick = new Imagick();
        $resolution = $image->resolution()->perInch();

        foreach ($image as $frame) {
            // create new frame canvas with modifiers background
            $canvas = new Imagick();
            $canvas->newImage($crop->width(), $crop->height(), $background, 'png');
            $canvas->setImageResolution($resolution->x(), $resolution->y());

            // set animation details
            if ($image->isAnimated()) {
                $canvas->setImageDelay($frame->native()->getImageDelay());
                $canvas->setImageIterations($frame->native()->getImageIterations());
                $canvas->setImageDispose($frame->native()->getImageDispose());
            }

            // place original frame content onto the empty colored frame canvas
            $canvas->compositeImage(
                $frame->native(),
                Imagick::COMPOSITE_OVER,
                ($crop->pivot()->x() + $this->offset_x) * -1,
                ($crop->pivot()->y() + $this->offset_y) * -1,
            );

            // copy alpha channel if available
            if ($frame->native()->getImageAlphaChannel()) {
                $canvas->compositeImage(
                    $frame->native(),
                    $this->imagemagickMajorVersion() <= 6 ? Imagick::COMPOSITE_DSTIN : Imagick::COMPOSITE_COPYOPACITY,
                    ($crop->pivot()->x() + $this->offset_x) * -1,
                    ($crop->pivot()->y() + $this->offset_y) * -1,
                );
            }

            // add newly built frame to container imagick
            $imagick->addImage($canvas);
        }

        // replace imagick
        $image->core()->setNative($imagick);

        return $image;
    }

    private function imagemagickMajorVersion(): int
    {
        if (preg_match('/^ImageMagick (?P<major>[0-9]+)\./', Imagick::getVersion()['versionString'], $matches) != 1) {
            return 0;
        }

        return intval($matches['major']);
    }
}
