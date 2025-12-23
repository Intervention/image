<?php

declare(strict_types=1);

namespace Intervention\Image\Colors;

abstract class FloatColorChannel extends AbstractColorChannel
{
    protected float $value;

    public function __construct(float $value)
    {
        $this->value = $this->validValueOrFail($value);
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
