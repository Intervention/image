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
        foreach ($this->prepareOptions($options) as $name => $value) {
            if (!property_exists($this, $name)) {
                throw new InputException('Property ' . $name . ' does not exists for ' . $this::class . '.');
            }

            $this->{$name} = $value;
        }

        return $this;
    }

    /**
     * This method makes it possible to call self::setOptions() with a single
     * array instead of named parameters
     *
     * @param array<mixed> $options
     * @return array<string, mixed>
     */
    private function prepareOptions(array $options): array
    {
        if (count($options) === 0) {
            return $options;
        }

        if (count($options) > 1) {
            return $options;
        }

        if (!array_key_exists(0, $options)) {
            return $options;
        }

        if (!is_array($options[0])) {
            return $options;
        }

        return $options[0];
    }
}
