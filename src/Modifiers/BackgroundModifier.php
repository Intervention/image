<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Colors\Rgb\Color;
use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Exceptions\ColorException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DriverInterface;

class BackgroundModifier extends SpecializableModifier
{
    /**
     * Create new modifier object
     *
     * @return void
     */
    public function __construct(public null|string|ColorInterface $color = null)
    {
        //
    }

    /**
     * Decode background color of current modifier with given driver. Possible
     * (semi-)transparent alpha channel values are made opaque.
     *
     * @throws RuntimeException
     * @throws ColorException
     */
    protected function backgroundColor(DriverInterface $driver): ColorInterface
    {
        // decode background color
        $color = $driver->handleColorInput(
            $this->color ?: $driver->config()->backgroundColor
        );

        // replace alpha channel value with opaque value
        if ($color->isTransparent()) {
            return new Color(
                $color->channel(Red::class)->value(),
                $color->channel(Green::class)->value(),
                $color->channel(Blue::class)->value(),
            );
        }

        return $color;
    }
}
