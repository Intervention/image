<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Closure;

interface DrawableFactoryInterface
{
    /**
     * Create a new factory instance statically
     */
    public static function init(null|Closure|DrawableInterface $init = null): self;

    /**
     * Create the end product of the factory
     */
    public function create(): DrawableInterface;

    /**
     * Define the background color of the drawable object
     */
    public function background(mixed $color): self;

    /**
     * Set the border size & color of the drawable object to be produced
     */
    public function border(mixed $color, int $size = 1): self;

    /**
     * Create the end product by invoking the factory
     */
    public function __invoke(): DrawableInterface;
}
