<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface DrawableFactoryInterface
{
    /**
     * Create the end product of the factory
     *
     * @return DrawableInterface
     */
    public function create(): DrawableInterface;

    /**
     * Create the end product by invoking the factory
     *
     * @return DrawableInterface
     */
    public function __invoke(): DrawableInterface;

    /**
     * Define the background color of the drawable object
     *
     * @param mixed $color
     * @return DrawableFactoryInterface
     */
    public function background(mixed $color): self;

    /**
     * Set the border size & color of the drawable object to be produced
     *
     * @param mixed $color
     * @param int $size
     * @return DrawableFactoryInterface
     */
    public function border(mixed $color, int $size = 1): self;
}
