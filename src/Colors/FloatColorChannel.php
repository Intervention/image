<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use Intervention\Image\Exceptions\InvalidArgumentException;

abstract class FloatColorChannel extends AbstractColorChannel
{
    /**
     * Main color channel value
     */
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
        if ($normalized < 0 || $normalized > 1) {
            throw new InvalidArgumentException(
                'Normalized color channel value of ' . static::class . ' must be in range 0 to 1',
            );
        }

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
