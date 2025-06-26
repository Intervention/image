<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface AnimationFactoryInterface
{
    /**
     * Resolve image from given source and add it as new animation frame with specific delay
     */
    public function add(mixed $source, float $delay = 1): self;

    /**
     * Create image instance
     */
    public function __invoke(): ImageInterface;
}
