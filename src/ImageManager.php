<?php

namespace Intervention\Image;

use Exception;
use ReflectionClass;
use Intervention\Image\Interfaces\ImageInterface;

class ImageManager
{
    /**
     * Configuration data
     *
     * @var array
     */
    protected $config = [
        'driver' => 'gd',
    ];

    /**
     * Create new instance
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->configure($config);
    }

    /**
     * Override configuration settings
     *
     * @param array $config
     */
    public function configure(array $config = []): self
    {
        $this->config = array_replace($this->config, $config);

        return $this;
    }

    /**
     * Return given value of configuration
     *
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    public function getConfig($key, $default = null)
    {
        return array_key_exists($key, $this->config) ? $this->config[$key] : $default;
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
        return $this->resolve('ImageFactory')->newImage($width, $height);
    }

    /**
     * Create new image instance from input
     *
     * @param  mixed $input
     * @return ImageInterface
     */
    public function make($input): ImageInterface
    {
        return $this->resolve('InputHandler')->handle($input);
    }

    /**
     * Resolve given classname according to current configuration
     *
     * @param  string $classname
     * @param  array  $arguments
     * @return mixed
     */
    private function resolve(string $classname, ...$arguments)
    {
        $classname = sprintf(
            "Intervention\\Image\\Drivers\\%s\\%s",
            ucfirst($this->config['driver']),
            $classname
        );

        $reflection = new ReflectionClass($classname);

        return $reflection->newInstanceArgs($arguments);
    }
}
