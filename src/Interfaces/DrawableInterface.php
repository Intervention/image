<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface DrawableInterface
{
    /**
     * Position of the drawable object.
     */
    public function position(): PointInterface;

    /**
     * Set position of the drawable object.
     */
    public function setPosition(PointInterface $position): self;

    /**
     * Set the background color of the drawable object.
     */
    public function setBackgroundColor(string|ColorInterface $color): self;

    /**
     * Return background color of drawable object.
     */
    public function backgroundColor(): null|string|ColorInterface;

    /**
     * Determine if a background color was set.
     */
    public function hasBackgroundColor(): bool;

    /**
     * Set border color & size of the drawable object.
     */
    public function setBorder(string|ColorInterface $color, int $size = 1): self;

    /**
     * Set border size of the drawable object.
     */
    public function setBorderSize(int $size): self;

    /**
     * Set border color of the drawable object.
     */
    public function setBorderColor(string|ColorInterface $color): self;

    /**
     * Get border size.
     */
    public function borderSize(): int;

    /**
     * Get border color of drawable object.
     */
    public function borderColor(): null|string|ColorInterface;

    /**
     * Determine if the drawable object has a border.
     */
    public function hasBorder(): bool;

    /**
     * Return the factory object with a copy of the current drawable object.
     */
    public function factory(): DrawableFactoryInterface;
}
