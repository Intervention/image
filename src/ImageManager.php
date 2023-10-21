<?php

namespace Intervention\Image;

use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Traits\CanResolveDriverClass;

class ImageManager
{
    use CanResolveDriverClass;

    private static $required_options = ['driver'];

    public function __construct(protected array $options = ['driver' => 'gd'])
    {
        if (count(array_intersect(array_keys($options), self::$required_options)) != count(self::$required_options)) {
            throw new Exceptions\ConfigurationException(
                'The following attributes are required to initialize ImageManager: ' .
                    implode(', ', self::$required_options)
            );
        }
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
        return strtolower($this->options['driver']);
    }
}
