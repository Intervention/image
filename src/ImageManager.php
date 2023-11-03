<?php

namespace Intervention\Image;

use Intervention\Image\Exceptions\ConfigurationException;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Traits\CanResolveDriverClass;

class ImageManager
{
    use CanResolveDriverClass;

    protected const AVAILABLE_DRIVERS = ['gd', 'imagick'];

    /**
     * Create new ImageManager instance
     *
     * @param string $driver
     * @return void
     * @throws ConfigurationException
     */
    public function __construct(protected string $driver = 'gd')
    {
        if (! in_array(strtolower($driver), self::AVAILABLE_DRIVERS)) {
            throw new ConfigurationException(
                'Driver ' . $driver . ' not available.'
            );
        }
    }

    /**
     * Static constructor to create ImageManager with given driver
     *
     * @param string $driver
     * @return ImageManager
     */
    public static function withDriver(string $driver): self
    {
        return new self($driver);
    }

    /**
     * Static helper to create ImageManager with GD driver
     *
     * @return ImageManager
     */
    public static function gd(): self
    {
        return new self('gd');
    }

    /**
     * Static constructor to create ImageManager with Imagick driver
     *
     * @return ImageManager
     */
    public static function imagick(): self
    {
        return new self('imagick');
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
     * Create new animated image from sources
     *
     * @param  callable $callback
     * @return ImageInterface
     */
    public function animate(callable $callback): ImageInterface
    {
        return $this->resolveDriverClass('ImageFactory')->newAnimation($callback);
    }

    /**
     * Create new image instance from source
     *
     * @param  mixed $source
     * @return ImageInterface
     */
    public function read($source): ImageInterface
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
