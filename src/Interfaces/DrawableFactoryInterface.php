<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface DrawableFactoryInterface
{
    /**
     * Create the drawable.
     */
    public static function build(null|callable|DrawableInterface $drawable = null): DrawableInterface;

    /**
     * Return the drawable.
     */
    public function drawable(): DrawableInterface;

    /**
     * Define the background color of the drawable object.
     */
    public function background(string|ColorInterface $color): self;

    /**
     * Set the border size & color of the drawable object to be produced.
     */
    public function border(string|ColorInterface $color, int $size = 1): self;
}
