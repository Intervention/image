<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

use Intervention\Image\Exceptions\InvalidArgumentException;

abstract class AlphaColorChannel extends AbstractColorChannel
{
    /**
     * Main color channel value
     */
    protected int $value;

    /**
     * @throws InvalidArgumentException
     */
    final public function __construct(float $value = 1)
    {
        if ($value < 0 || $value > 1) {
            throw new InvalidArgumentException(
                'Color channel value of ' . static::class . ' must be in range 0 to 1',
            );
        }

        $this->value = $this->validValueOrFail(intval(round($value * $this->max())));
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
        return new static($normalized);
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
     * @see ColorChannelInterface::min()
     */
    public static function min(): float
    {
        return 0;
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorChannelInterface::max()
     */
    public static function max(): float
    {
        return 255;
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorChannelInterface::scale()
     */
    public function scale(int $percent): self
    {
        if ($percent === 0) {
            return $this;
        }

        if ($percent < -100 || $percent > 100) {
            throw new InvalidArgumentException('Percentage change must be between -100 and 100');
        }

        $base = $percent >= 0 ? ((static::max() - static::min()) - $this->value) : $this->value;
        $delta = (int) round($base / 100 * $percent);
        $this->value = (int) round(max(static::min(), min(static::max(), $this->value + $delta)));

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see ColorChannelInterface::toString()
     */
    public function toString(): string
    {
        return strval($this->normalizedValue(2));
    }
}
