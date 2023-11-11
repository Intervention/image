<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Drivers\Abstract\Modifiers\AbstractRotateModifier;
use Intervention\Image\Drivers\Gd\Traits\CanHandleColors;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Traits\CanBuildNewImage;

class RotateModifier extends AbstractRotateModifier implements ModifierInterface
{
    use CanHandleColors;
    use CanBuildNewImage;

    public function apply(ImageInterface $image): ImageInterface
    {
        $background = $this->handleInput($this->background);

        foreach ($image as $frame) {
            $this->modify($frame, $background);
        }

        return $image;
    }

    protected function modify(FrameInterface $frame, ColorInterface $background): void
    {
        // rotate original image against transparent background
        $rotated = imagerotate(
            $frame->core(),
            $this->rotationAngle(),
            imagecolorallocatealpha(
                $frame->core(),
                $background->channel(Red::class)->value(),
                $background->channel(Green::class)->value(),
                $background->channel(Blue::class)->value(),
                127
            )
        );

        // create size from original after rotation
        $container = (new Rectangle(
            imagesx($rotated),
            imagesy($rotated),
        ))->movePivot('center');

        // create size from original and rotate points
        $cutout = (new Rectangle(
            imagesx($frame->core()),
            imagesy($frame->core()),
            $container->pivot()
        ))->align('center')
            ->valign('center')
            ->rotate($this->rotationAngle() * -1);

        // create new gd image
        $modified = $this->imageFactory()->newCore(
            imagesx($rotated),
            imagesy($rotated),
            $background
        );

        // define transparent colors
        $transparent = imagecolorallocatealpha($modified, 255, 0, 255, 127);
        imagecolortransparent($modified, $transparent);

        // draw the cutout on new gd image to have a transparent
        // background where the rotated image will be placed
        imagealphablending($modified, false);
        imagefilledpolygon(
            $modified,
            $cutout->toArray(),
            $transparent
        );

        // place rotated image on new gd image
        imagealphablending($modified, true);
        imagecopy(
            $modified,
            $rotated,
            0,
            0,
            0,
            0,
            imagesx($rotated),
            imagesy($rotated)
        );

        $frame->setCore($modified);
    }
}
