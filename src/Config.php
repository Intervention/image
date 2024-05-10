<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Exceptions\InputException;
use Intervention\Image\Interfaces\ConfigInterface;

class Config implements ConfigInterface
{
    public const AUTO_ORIENTATION = 'autoOrientation';
    public const DECODE_ANIMATION = 'decodeAnimation';
    public const BLENDING_COLOR = 'blendingColor';

    /**
     * Create config object instance
     *
     * @param bool $autoOrientation
     * @param bool $decodeAnimation
     * @param mixed $blendingColor
     * @return void
     */
    public function __construct(
        protected bool $autoOrientation = true,
        protected bool $decodeAnimation = true,
        protected mixed $blendingColor = 'ffffff00',
    ) {
    }

    /**
     * {@inheritdoc}
     *
     * @see ConfigInterface::option()
     */
    public function option(string $name, mixed $default = null): mixed
    {
        if (!property_exists($this, $name)) {
            return $default;
        }

        return $this->{$name};
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
