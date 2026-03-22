<?php

declare(strict_types=1);

namespace Intervention\Image\Modifiers;

use Intervention\Image\Drivers\SpecializableModifier;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DriverInterface;

class FillTransparentAreasModifier extends SpecializableModifier
{
    /**
     * Create new modifier object.
     */
    public function __construct(public null|string|ColorInterface $color = null)
    {
        //
    }

    /**
     * Decode background color of current modifier with given driver.
     */
    protected function backgroundColor(DriverInterface $driver): ColorInterface
    {
        return $driver->decodeColor(
            $this->color !== null ? $this->color : $driver->config()->backgroundColor
        );
    }
}
