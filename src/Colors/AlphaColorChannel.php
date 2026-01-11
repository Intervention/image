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
    final public function __construct(float $value)
    {
        $this->value = intval(round($this->validValueOrFail($value) * $this->max()));
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
     * @see ColorChannelInterface::toString()
     */
    public function toString(): string
    {
        return strval(round($this->value() / $this->max(), 2));
    }
}
