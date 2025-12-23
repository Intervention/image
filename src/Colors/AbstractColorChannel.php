<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Stringable;

abstract class AbstractColorChannel implements ColorChannelInterface, Stringable
{
    /**
     * {@inheritdoc}
     *
     * @see ColorChannelInterface::fromNormalized()
     */
    public static function fromNormalized(float $normalized): self
    {
        return new static(static::min() + $normalized * (static::max() - static::min()));
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorChannelInterface::normalize()
     */
    public function normalize(int $precision = 32): float
    {
        return round(($this->value() - $this->min()) / ($this->max() - $this->min()), $precision);
    }

    /**
     * Throw exception if the given value is not applicable for channel
     * otherwise the value is returned unchanged.
     */
    protected function validValueOrFail(int|float $value): mixed
    {
        if ($value < $this->min() || $value > $this->max()) {
            throw new InvalidArgumentException(
                'Color channel ' . $this::class . ' value must be in range ' . $this->min() . ' to ' . $this->max(),
            );
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorChannelInterface::toString()
     */
    public function toString(): string
    {
        return (string) $this->value();
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorChannelInterface::__toString()
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}
