<?php

namespace Intervention\Image;

use Closure;

class ImageManagerStatic
{
    /**
     * Instance of Intervention\Image\ImageManager
     *
     * @var ImageManager
     */
    public $manager;

    /**
     * Creates a new instance
     *
     * @param ImageManager $manager
     */
    public function __construct(ImageManager $manager = null)
    {
        $this->manager = $manager ? $manager : new ImageManager;
    }

    /**
     * Creates a new instance
     *
     * @return Intervention\Image\ImageManagerStatic
     */
    public static function newInstance()
    {
        return new self;
    }

    /**
     * Statically initiates an Image instance from different input types
     *
     * @param  mixed $data
     *
     * @return Intervention\Image\Image
     */
    public static function make($data)
    {
        return self::newInstance()->manager->make($data);
    }

    /**
     * Statically creates an empty image canvas
     *
     * @param  integer $width
     * @param  integer $height
     * @param  mixed $background
     *
     * @return Intervention\Image\Image
     */
    public static function canvas($width, $height, $background = null)
    {
        return self::newInstance()->manager->canvas($width, $height, $background);
    }

    /**
     * Create new cached image and run callback statically
     *
     * @param  Closure  $callback
     * @param  integer  $lifetime
     * @param  boolean  $returnObj
     *
     * @return mixed
     */
    public static function cache(Closure $callback, $lifetime = null, $returnObj = false)
    {
        return self::newInstance()->manager->cache($callback, $lifetime, $returnObj);
    }
}
