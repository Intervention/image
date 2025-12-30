<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use Intervention\Image\Exceptions\InvalidArgumentException;

abstract class FloatColorChannel extends AbstractColorChannel
{
    protected float $value;

    /**
     * @throws InvalidArgumentException
     */
    final public function __construct(float $value)
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
        return new static(static::min() + $normalized * (static::max() - static::min()));
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorChannelInterface::value()
     */
    public function value(): float
    {
        return $this->value;
    }
}
