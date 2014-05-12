<?php

namespace Intervention\Image;

class ImageManagerStatic
{
    public $manager;

    public function __construct(ImageManager $manager = null)
    {
        $this->manager = $manager ? $manager : new ImageManager;
    }

    public static function newInstance()
    {
        return new self;
    }

    public static function make($data)
    {
        return self::newInstance()->manager->make($data);
    }

    public static function canvas($width, $height, $background = null)
    {
        return self::newInstance()->manager->canvas($width, $height, $background);
    }
}
