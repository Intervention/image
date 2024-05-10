<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Exceptions\InputException;

class Config
{
    /**
     * Create config object instance
     *
     * @param bool $autoOrientation
     * @param bool $decodeAnimation
     * @param mixed $blendingColor
     * @return void
     */
    public function __construct(
        public bool $autoOrientation = true,
        public bool $decodeAnimation = true,
        public mixed $blendingColor = 'ffffff',
    ) {
    }

    /**
     * Set values of given config options
     *
     * @param mixed $options
     * @throws InputException
     * @return Config
     */
    public function setOptions(mixed ...$options): self
    {
        foreach ($options as $name => $value) {
            if (!property_exists($this, $name)) {
                throw new InputException('Property ' . $name . ' does not exists for ' . $this::class . '.');
            }

            $this->{$name} = $value;
        }

        return $this;
    }
}
