<?php

namespace Intervention\Image;

use Intervention\Image\Exceptions\ConfigurationException;
use Intervention\Image\Interfaces\ImageInterface;

class ImageManager
{
    /**
     * Create new ImageManager instance
     *
     * @param string $driver
     * @return void
     * @throws ConfigurationException
     */
    public function __construct(protected string $driver = 'gd')
    {
        if (!$this->driverExists()) {
            throw new ConfigurationException('Driver ' . $driver . ' is not available.');
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
        return $this->resolveDriverClass('Factory')->newImage($width, $height);
    }

    /**
     * Create new animated image from sources
     *
     * @param  callable $callback
     * @return ImageInterface
     */
    public function animate(callable $callback): ImageInterface
    {
        return $this->resolveDriverClass('Factory')->newAnimation($callback);
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
     * Resolve given classname with configured driver
     *
     * @return object
     */
    private function resolveDriverClass(string $classname): object
    {
        $classname = $this->driverClassname($classname);

        return new $classname();
    }

    /**
     * Build full namespaced classname of given class for configured driver
     *
     * @param string $classname
     * @return string
     */
    private function driverClassname(string $classname): string
    {
        return sprintf(
            "Intervention\Image\Drivers\%s\%s",
            ucfirst($this->driver),
            $classname
        );
    }

    /**
     * Determine if configured driver exists
     *
     * @return bool
     */
    private function driverExists(): bool
    {
        return class_exists($this->driverClassname('Image'));
    }
}
