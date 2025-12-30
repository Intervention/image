<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Gd\Modifiers;

use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\FrameInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\FillModifier as GenericFillModifier;

class FillModifier extends GenericFillModifier implements SpecializedInterface
{
    /**
     * {@inheritdoc}
     *
     * @see ModifierInterface::apply()
     *
     * @throws ModifierException
     * @throws StateException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        $color = $this->color($image);

        foreach ($image as $frame) {
            if ($this->hasPosition()) {
                $this->floodFillWithColor($frame, $color);
            } else {
                $this->fillAllWithColor($frame, $color);
            }
        }

        return $image;
    }

    /**
     * @throws StateException
     */
    private function color(ImageInterface $image): int
    {
        return $this->driver()->colorProcessor($image->colorspace())->colorToNative(
            $this->driver()->handleColorInput($this->color)
        );
    }

    /**
     * @throws ModifierException
     */
    private function floodFillWithColor(FrameInterface $frame, int $color): void
    {
        $result = imagefill(
            $frame->native(),
            $this->position->x(),
            $this->position->y(),
            $color
        );

        if ($result === false) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to flood fill image'
            );
        }
    }

    /**
     * @throws ModifierException
     */
    private function fillAllWithColor(FrameInterface $frame, int $color): void
    {
        $result = imagealphablending($frame->native(), true);

        if ($result === false) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to set alpha blending',
            );
        }

        $result = imagefilledrectangle(
            $frame->native(),
            0,
            0,
            $frame->size()->width() - 1,
            $frame->size()->height() - 1,
            $color
        );

        if ($result === false) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to fill image with rectangle',
            );
        }
    }
}
