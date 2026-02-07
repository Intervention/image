<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Alignment;
use Intervention\Image\Colors\Rgb\Color as RgbColor;
use Intervention\Image\Colors\Rgb\Colorspace as Rgb;
use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\RotateModifier as GenericRotateModifier;
use Intervention\Image\Size;

class RotateModifier extends GenericRotateModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     *
     * @throws InvalidArgumentException
     * @throws ModifierException
     * @throws StateException
     * @throws DriverException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $background = $this->backgroundColor();

        foreach ($image as $frame) {
            $this->modifyFrame($frame, $background);
        }

        return $image;
    }

    /**
     * Apply rotation modification on given frame, given background
     * color is used for newly create image areas
     *
     * @throws InvalidArgumentException
     * @throws ModifierException
     * @throws DriverException
     */
    protected function modifyFrame(FrameInterface $frame, ColorInterface $background): void
    {
        // normalize color to rgb colorspace
        $background = $background->toColorspace(Rgb::class);

        if (!$background instanceof RgbColor) {
            throw new ModifierException('Failed to normalize background color to rgb color space');
        }

        // get transparent color from frame core
        $transparent = match ($transparent = imagecolortransparent($frame->native())) {
            -1 => imagecolorallocatealpha(
                $frame->native(),
                $background->red()->value(),
                $background->green()->value(),
                $background->blue()->value(),
                127
            ),
            default => $transparent,
        };

        // rotate original image against transparent background
        $rotated = imagerotate(
            $frame->native(),
            $this->rotationAngle() * -1,
            $transparent
        );

        // create size from original after rotation
        $container = (new Size(
            imagesx($rotated),
            imagesy($rotated),
        ))->movePivot(Alignment::CENTER);

        // create size from original and rotate points
        $cutout = (new Size(
            imagesx($frame->native()),
            imagesy($frame->native()),
            $container->pivot()
        ))->alignHorizontally(Alignment::CENTER)
            ->alignVertically(Alignment::CENTER)
            ->rotate($this->rotationAngle());

        // create new gd image
        $modified = Cloner::cloneEmpty($frame->native(), $container, $background);

        // draw the cutout on new gd image to have a transparent
        // background where the rotated image will be placed
        imagealphablending($modified, false);
        imagefilledpolygon(
            $modified,
            $cutout->coordinates(),
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
