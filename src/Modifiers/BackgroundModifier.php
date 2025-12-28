<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;
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
     * Decode background color of current modifier with given driver
     */
    protected function backgroundColor(DriverInterface $driver): ColorInterface
    {
        return $driver->handleColorInput(
            $this->color !== null ? $this->color : $driver->config()->backgroundColor
        );
    }
}
