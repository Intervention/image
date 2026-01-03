<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

interface AnimationFactoryInterface
{
    /**
     * Resolve image from given source and add it as new animation
     * frame with specific delay in seconds.
     */
    public function add(mixed $source, float $delay = 1): self;

    /**
     * Build ready-made animation as end product.
     */
    public function animation(): ImageInterface;
}
