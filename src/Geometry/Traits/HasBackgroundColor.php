<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry\Traits;

trait HasBackgroundColor
{
    protected mixed $backgroundColor = null;

    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::setBackgroundColor()
     */
    public function setBackgroundColor(mixed $color): self
    {
        $this->backgroundColor = $color;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::backgroundColor()
     */
    public function backgroundColor(): mixed
    {
        return $this->backgroundColor;
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::hasBackgroundColor()
     */
    public function hasBackgroundColor(): bool
    {
        return !empty($this->backgroundColor);
    }
}
