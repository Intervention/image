<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry\Traits;

use Intervention\Image\Interfaces\ColorInterface;

trait HasBackgroundColor
{
    protected null|string|ColorInterface $backgroundColor = null;

    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::setBackgroundColor()
     */
    public function setBackgroundColor(string|ColorInterface $color): self
    {
        $this->backgroundColor = $color;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::backgroundColor()
     */
    public function backgroundColor(): null|string|ColorInterface
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
        return $this->backgroundColor !== null;
    }
}
