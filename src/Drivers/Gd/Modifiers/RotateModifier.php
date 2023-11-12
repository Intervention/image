<?php

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Drivers\Abstract\Modifiers\AbstractRotateModifier;
use Intervention\Image\Drivers\Gd\Traits\CanHandleColors;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Exceptions\MissingDriverComponentException;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Traits\CanBuildNewImage;
use ReflectionException;

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

    /**
     * Apply rotation modification on given frame, given background
     * color is used for newly create image areas
     *
     * @param FrameInterface $frame
     * @param ColorInterface $background
     * @return void
     * @throws RuntimeException
     * @throws MissingDriverComponentException
     * @throws ReflectionException
     */
    protected function modify(FrameInterface $frame, ColorInterface $background): void
    {
        // get transparent color from frame core
        $transparent = match ($transparent = imagecolortransparent($frame->core())) {
            -1 => imagecolorallocatealpha(
                $frame->core(),
                $background->channel(Red::class)->value(),
                $background->channel(Green::class)->value(),
                $background->channel(Blue::class)->value(),
                127
            ),
            default => $transparent,
        };

        // rotate original image against transparent background
        $rotated = imagerotate(
            $frame->core(),
            $this->rotationAngle(),
            $transparent
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

        // create new gd core
        $modified = $this->imageFactory()->newCore(
            imagesx($rotated),
            imagesy($rotated),
            $background
        );

        // draw the cutout on new gd image to have a transparent
        // background where the rotated image will be placed
        imagealphablending($modified, false);
        imagefilledpolygon(
            $modified,
            $cutout->toArray(),
            imagecolortransparent($modified)
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
