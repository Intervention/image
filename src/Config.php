<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Exceptions\InputException;
use Intervention\Image\Interfaces\ConfigInterface;

class Config implements ConfigInterface
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
     * {@inheritdoc}
     *
     * @see ConfigInterface::setOption()
     */
    public function setOption(string $name, mixed $value): self
    {
        if (!property_exists($this, $name)) {
            throw new InputException('Property ' . $name . ' does not exists for ' . $this::class . '.');
        }

        $this->{$name} = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see COnfigInterface::setOptions()
     */
    public function setOptions(mixed ...$options): self
    {
        foreach ($options as $name => $value) {
            $this->setOption($name, $value);
        }

        return $this;
    }
}
