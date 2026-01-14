<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Modifiers;

use ImagickDraw;
use ImagickException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use Intervention\Image\Modifiers\DrawLineModifier as GenericDrawLineModifier;

class DrawLineModifier extends GenericDrawLineModifier implements SpecializedInterface
{
    /**
     * @throws ModifierException
     * @throws StateException
     */
    public function apply(ImageInterface $image): ImageInterface
    {
        try {
            $drawing = new ImagickDraw();
            $drawing->setStrokeWidth($this->drawable->width());
            $drawing->setFillOpacity(0);

            if ($this->drawable->hasBackgroundColor()) {
                $drawing->setStrokeColor(
                    $this->driver()->colorProcessor($image)->colorToNative(
                        $this->backgroundColor()
                    )
                );
            }

            $drawing->line(
                $this->drawable->start()->x(),
                $this->drawable->start()->y(),
                $this->drawable->end()->x(),
                $this->drawable->end()->y(),
            );
        } catch (ImagickException $e) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to build ImagickDraw object',
                previous: $e
            );
        }

        foreach ($image as $frame) {
            $result = $frame->native()->drawImage($drawing);
            if ($result === false) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable draw line on image',
                );
            }
        }

        return $image;
    }
}
