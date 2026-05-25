<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers\Imagick\Traits;

use Imagick;
use ImagickDraw;
use ImagickException;
use Intervention\Image\Exceptions\ModifierException;

trait CanDraw
{
    /**
     * Draw given element on canvas.
     *
     * @throws ModifierException
     */
    protected function draw(Imagick $canvas, ImagickDraw $element): void
    {
        try {
            $result = $canvas->drawImage($element);

            if ($result === false) {
                throw new ModifierException(
                    'Failed to apply ' . self::class . ', unable to draw pixel on image',
                );
            }
        } catch (ImagickException $e) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', unable to draw pixel on image',
                previous: $e,
            );
        }
    }
}
