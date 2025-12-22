<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\ColorChannelInterface;
use Stringable;

abstract class AbstractColorChannel implements ColorChannelInterface, Stringable
{
    protected int $value;

    /**
     * {@inheritdoc}
     *
     * @see ColorChannelInterface::__construct()
     */
    public function __construct(?int $value = null, ?float $normalized = null)
    {
        $this->value = $this->validValueOrFail(
            match (true) {
                is_null($value) && is_numeric($normalized) => intval(round($normalized * $this->max())),
                is_numeric($value) && is_null($normalized) => $value,
                default => throw new InvalidArgumentException(
                    'Color channels must either have a value or a normalized value',
                )
            }
        );
    }

    /**
     * Alias of value()
     */
    public function toInt(): int
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorChannelInterface::value()
     */
    public function value(): int
    {
        return $this->value;
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
     * {@inheritdoc}
     *
     * @see ColorChannelInterface::toString()
     */
    public function toString(): string
    {
        return (string) $this->value();
    }

    /**
     * Throw exception if the given value is not applicable for channel
     * otherwise the value is returned unchanged.
     */
    private function validValueOrFail(int|float $value): mixed
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
     * @see ColorChannelInterface::__toString()
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}
