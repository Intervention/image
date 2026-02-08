<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface DrawableFactoryInterface
{
    /**
     * Create the end product of the factory statically by calling given callable
     */
    public static function build(null|callable|DrawableInterface $drawable = null): DrawableInterface;

    /**
     * Create the end product of the factory.
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
