<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface DrawableInterface
{
    /**
     * Position of the drawable object
     */
    public function position(): PointInterface;

    /**
     * Set position of the drawable object
     */
    public function setPosition(PointInterface $position): self;

    /**
     * Set the background color of the drawable object
     */
    public function setBackgroundColor(mixed $color): self;

    /**
     * Return background color of drawable object
     */
    public function backgroundColor(): mixed;

    /**
     * Determine if a background color was set
     */
    public function hasBackgroundColor(): bool;

    /**
     * Set border color & size of the drawable object
     */
    public function setBorder(mixed $color, int $size = 1): self;

    /**
     * Set border size of the drawable object
     */
    public function setBorderSize(int $size): self;

    /**
     * Set border color of the drawable object
     */
    public function setBorderColor(mixed $color): self;

    /**
     * Get border size
     */
    public function borderSize(): int;

    /**
     * Get border color of drawable object
     */
    public function borderColor(): mixed;

    /**
     * Determine if the drawable object has a border
     */
    public function hasBorder(): bool;
}
