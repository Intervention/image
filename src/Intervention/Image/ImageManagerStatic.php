<?php

namespace Intervention\Image;

class ImageManagerStatic
{
    /**
     * Instance of Intervention\Image\ImageManager
     *
     * @var Intervention\Image\ImageManager
     */
    public $manager;

    /**
     * Creates a new instance
     *
     * @param Intervention\Image\ImageManager $manager
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
     * @return Intervention\Image\Image
     */
    public static function canvas($width, $height, $background = null)
    {
        return self::newInstance()->manager->canvas($width, $height, $background);
    }
}
