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

        $imagick = new Imagick();

        foreach ($image as $frame) {
            $canvas = new Imagick();
            $canvas->newImage($crop->width(), $crop->height(), $background, 'png');

            $canvas->compositeImage(
                $frame->native(),
                Imagick::COMPOSITE_OVER,
                ($crop->pivot()->x() + $this->offset_x) * -1,
                ($crop->pivot()->y() + $this->offset_y) * -1,
            );

            $canvas->compositeImage(
                $frame->native(),
                $this->imagemagickMajorVersion() <= 6 ? Imagick::COMPOSITE_DSTIN : Imagick::COMPOSITE_COPYOPACITY,
                ($crop->pivot()->x() + $this->offset_x) * -1,
                ($crop->pivot()->y() + $this->offset_y) * -1,
            );

            $imagick->addImage($canvas);
        }

        $image->core()->setNative($imagick);

        return $image;
    }

    private function imagemagickMajorVersion(): int
    {
        if (preg_match('/^ImageMagick (?P<major>[0-9]+)\./', Imagick::getVersion()['versionString'], $matches) != 1) {
            return 0;
        }

        return intval($matches['major'] ?? 0);
    }
}
