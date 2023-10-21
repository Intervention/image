<?php

namespace Intervention\Image\Interfaces;

interface FactoryInterface
{
    /**
     * Create new image in the given size
     *
     * @param int $width
     * @param int $height
     * @return ImageInterface
     */
    public function newImage(int $width, int $height): ImageInterface;

    /**
     * Create new animated image
     *
     * @param callable $callback
     * @return ImageInterface
     */
    public function newAnimation(callable $callback): ImageInterface;
}
