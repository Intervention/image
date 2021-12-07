<?php

namespace Intervention\Image;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Traits\CanResolveDriverClass;

class ImageManager
{
    use CanResolveDriverClass;

    public function __construct(protected string $driver = 'gd')
    {
        //
    }

    /**
     * Create new image instance from scratch
     *
     * @param  int    $width
     * @param  int    $height
     * @return ImageInterface
     */
    public function create(int $width, int $height): ImageInterface
    {
        return $this->resolveDriverClass('ImageFactory')->newImage($width, $height);
    }

    /**
     * Create new image instance from source
     *
     * @param  mixed $source
     * @return ImageInterface
     */
    public function make($source): ImageInterface
    {
        return $this->resolveDriverClass('InputHandler')->handle($source);
    }

    /**
     * Return id of current driver
     *
     * @return string
     */
    protected function getCurrentDriver(): string
    {
        return strtolower($this->driver);
    }
}
