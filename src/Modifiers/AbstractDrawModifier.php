<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Color;
use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Exceptions\ColorDecoderException;
use Intervention\Image\Exceptions\ModifierException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DrawableInterface;

abstract class AbstractDrawModifier extends SpecializableModifier
{
    /**
     * Return the drawable object which will be rendered by the modifier.
     */
    abstract protected function drawable(): DrawableInterface;

    /**
     * Return the background color of the object rendered by the modifier.
     *
     * @throws StateException
     * @throws ColorDecoderException
     */
    protected function backgroundColor(): ColorInterface
    {
        $backgroundColor = $this->drawable()->backgroundColor();

        if ($backgroundColor === null) {
            return Color::transparent();
        }

        return $this->driver()->decodeColor($backgroundColor);
    }

    /**
     * Return the border color of the object rendered by the modifier.
     *
     * @throws StateException
     * @throws ColorDecoderException
     */
    protected function borderColor(): ColorInterface
    {
        $borderColor = $this->drawable()->borderColor();

        if ($borderColor === null || $this->drawable()->hasBorder() === false) {
            return Color::transparent();
        }

        return $this->driver()->decodeColor($borderColor);
    }

    /**
     * Throw ModifierException with given message if result is false.
     *
     * @throws ModifierException
     */
    protected function abortUnless(mixed $result, string $message): void
    {
        if ($result === false) {
            throw new ModifierException(
                'Failed to apply ' . self::class . ', ' . $message,
            );
        }
    }
}
