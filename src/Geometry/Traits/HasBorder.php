<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry\Traits;

use Intervention\Image\Exceptions\InvalidArgumentException;

trait HasBorder
{
    protected mixed $borderColor = null;
    protected int $borderSize = 0;

    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::setBorder()
     */
    public function setBorder(mixed $color, int $size = 1): self
    {
        return $this->setBorderSize($size)->setBorderColor($color);
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::setBorderSize()
     */
    public function setBorderSize(int $size): self
    {
        if ($size < 0) {
            throw new InvalidArgumentException(
                'Border size must be greater than or equal to 0'
            );
        }

        $this->borderSize = $size;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::borderSize()
     */
    public function borderSize(): int
    {
        return $this->borderSize;
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::setBorderColor()
     */
    public function setBorderColor(mixed $color): self
    {
        $this->borderColor = $color;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::borderColor()
     */
    public function borderColor(): mixed
    {
        return $this->borderColor;
    }

    /**
     * {@inheritdoc}
     *
     * @see DrawableInterface::hasBorder()
     */
    public function hasBorder(): bool
    {
        return $this->borderSize > 0 && !is_null($this->borderColor);
    }
}
