<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Alignment;
use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Colors\Rgb\Colorspace as Rgb;
use Intervention\Image\Drivers\Gd\Cloner;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\RotateModifier as GenericRotateModifier;

class RotateModifier extends GenericRotateModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
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
     */
    protected function modifyFrame(FrameInterface $frame, ColorInterface $background): void
    {
        // normalize color to rgb colorspace
        $background = $background->toColorspace(Rgb::class);

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

        if ($transparent === false) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to allocate transparent color',
            );
        }

        // rotate original image against transparent background
        $rotated = imagerotate(
            $frame->native(),
            $this->rotationAngle(),
            $transparent
        );

        if ($rotated === false) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable rotate image',
            );
        }

        // create size from original after rotation
        $container = (new Rectangle(
            imagesx($rotated),
            imagesy($rotated),
        ))->movePivot(Alignment::CENTER);

        // create size from original and rotate points
        $cutout = (new Rectangle(
            imagesx($frame->native()),
            imagesy($frame->native()),
            $container->pivot()
        ))->align(Alignment::CENTER)
            ->valign(Alignment::CENTER)
            ->rotate($this->rotationAngle() * -1);

        // create new gd image
        $modified = Cloner::cloneEmpty($frame->native(), $container, $background);

        // draw the cutout on new gd image to have a transparent
        // background where the rotated image will be placed
        $result = imagealphablending($modified, false);

        if ($result === false) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to set alpha blending',
            );
        }

        $result = imagefilledpolygon(
            $modified,
            $cutout->toArray(),
            imagecolortransparent($modified)
        );

        if ($result === false) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to fill image background',
            );
        }

        // place rotated image on new gd image
        $result = imagealphablending($modified, true);

        if ($result === false) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to set alpha blending',
            );
        }

        $result = imagecopy(
            $modified,
            $rotated,
            0,
            0,
            0,
            0,
            imagesx($rotated),
            imagesy($rotated)
        );

        if ($result === false) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to copy rotated image',
            );
        }

        $frame->setNative($modified);
    }
}
