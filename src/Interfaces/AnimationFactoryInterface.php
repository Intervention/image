<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface AnimationFactoryInterface
{
    /**
     * Create the end product of the factory statically by calling given callable
     */
    public static function build(DriverInterface $driver, callable $animation): ImageInterface;

    /**
     * Resolve image from given source and add it as new animation frame with specific delay.
     */
    public function add(mixed $source, float $delay = 1): self;

    /**
     * Create image instance.
     */
    public function animation(): ImageInterface;
}
