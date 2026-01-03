<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface DrawableFactoryInterface
{
    /**
     * Create a new factory instance statically.
     */
    public static function create(null|callable|DrawableInterface $init = null): self; // todo: maybe switch to callable

    /**
     * Create the end product of the factory statically by calling given callable
     */
    public static function build(?callable $init = null): DrawableInterface;

    /**
     * Create the end product of the factory.
     */
    public function drawable(): DrawableInterface;

    /**
     * Define the background color of the drawable object.
     */
    public function background(mixed $color): self;

    /**
     * Set the border size & color of the drawable object to be produced.
     */
    public function border(mixed $color, int $size = 1): self;

    /**
     * Create the end product by invoking the factory.
     */
    public function __invoke(): DrawableInterface; // todo: maybe remove
}
