<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use Intervention\Image\Exceptions\InvalidArgumentException;

abstract class IntegerColorChannel extends AbstractColorChannel
{
    /**
     * Main color channel value
     */
    protected int $value;

    /**
     * @throws InvalidArgumentException
     */
    final public function __construct(int $value)
    {
        $this->value = $this->validValueOrFail($value);
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorChannelInterface::fromNormalized()
     *
     * @throws InvalidArgumentException
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
     * {@inheritdoc}
     *
     * @see ColorChannelInterface::normalize()
     */
    public function normalize(int $precision = 32): float
    {
        return round(($this->value() - $this->min()) / ($this->max() - $this->min()), $precision);
    }
}
