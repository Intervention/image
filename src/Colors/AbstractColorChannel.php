<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Stringable;

abstract class AbstractColorChannel implements ColorChannelInterface, Stringable
{
    /**
     * Main color channel value
     */
    protected int|float $value;

    /**
     * {@inheritdoc}
     *
     * @see ColorChannelInterface::normalizedValue()
     */
    public function normalizedValue(int $precision = 32): float
    {
        return round(($this->value() - $this->min()) / ($this->max() - $this->min()), $precision);
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorChannelInterface::scale()
     *
     * @throws InvalidArgumentException
     */
    public function scale(int $percent): self
    {
        if ($percent === 0) {
            return $this;
        }

        if ($percent < -100 || $percent > 100) {
            throw new InvalidArgumentException('Percentage value must be between -100 and 100');
        }

        $normalized = $this->normalizedValue();
        $base = $percent >= 0 ? (1 - $normalized) : $normalized;
        $scaled = min(1.0, max(0.0, $normalized + $base / 100 * $percent));
        $this->value = static::fromNormalized($scaled)->value();

        return $this;
    }

    /**
     * Throw exception if the given value is not applicable for channel
     * otherwise the value is returned unchanged.
     *
     * @throws InvalidArgumentException
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
