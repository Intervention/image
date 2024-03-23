<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\RotateModifier as GenericRotateModifier;

class RotateModifier extends GenericRotateModifier implements SpecializedInterface
{
    public function apply(ImageInterface $image): ImageInterface
    {
        $background = $this->driver()->handleInput($this->background);

        foreach ($image as $frame) {
            $this->modifyFrame($frame, $background);
        }

        return $image;
    }

    /**
     * Apply rotation modification on given frame, given background
     * color is used for newly create image areas
     *
     * @param FrameInterface $frame
     * @throws ColorException
     * @param ColorInterface $background
     * @return void
     */
    protected function modifyFrame(FrameInterface $frame, ColorInterface $background): void
    {
        // get transparent color from frame core
        $transparent = match ($transparent = imagecolortransparent($frame->native())) {
            -1 => imagecolorallocatealpha(
                $frame->native(),
                $background->channel(Red::class)->value(),
                $background->channel(Green::class)->value(),
                $background->channel(Blue::class)->value(),
                127
            ),
            default => $transparent,
        };

        // rotate original image against transparent background
        $rotated = imagerotate(
            $frame->native(),
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
            imagesx($frame->native()),
            imagesy($frame->native()),
            $container->pivot()
        ))->align('center')
            ->valign('center')
            ->rotate($this->rotationAngle() * -1);

        // create new gd image
        $modified = Cloner::cloneEmpty($frame->native(), $container, $background);

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

        $frame->setNative($modified);
    }
}
