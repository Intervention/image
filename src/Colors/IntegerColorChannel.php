<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

abstract class IntegerColorChannel extends AbstractColorChannel
{
    protected int $value;

    public function __construct(int $value)
    {
        $this->value = $this->validValueOrFail($value);
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorChannelInterface::fromNormalized()
     */
    public static function fromNormalized(float $normalized): self
    {
        return new static(intval(round($normalized * static::max())));
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
     * Alias of value()
     */
    public function toInt(): int
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
}
