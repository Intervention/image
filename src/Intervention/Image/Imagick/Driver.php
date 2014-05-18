<?php

namespace Intervention\Image\Imagick;

use \Intervention\Image\Size;

class Driver extends \Intervention\Image\AbstractDriver
{
    /**
     * Creates new instance of driver
     *
     * @param Intervention\Image\Imagick\Source  $source
     * @param Intervention\Image\Imagick\Encoder $encoder
     */
    public function __construct(Source $source = null, Encoder $encoder = null)
    {
        if ( ! $this->coreAvailable()) {
            throw new \Intervention\Image\Exception\NotSupportedException(
                "ImageMagick module not available with this PHP installation."
            );
        }

        $this->source = $source ? $source : new Source;
        $this->encoder = $encoder ? $encoder : new Encoder;
    }

    /**
     * Creates new image instance
     *
     * @param  integer $width
     * @param  integer $height
     * @param  string  $background
     * @return Intervention\Image\Image
     */
    public function newImage($width, $height, $background = null)
    {
        $background = new Color($background);

        // create empty core
        $core = new \Imagick;
        $core->newImage($width, $height, $background->getPixel(), 'png');
        $core->setType(\Imagick::IMGTYPE_UNDEFINED);
        $core->setImagetype(\Imagick::IMGTYPE_UNDEFINED);
        $core->setColorspace(\Imagick::COLORSPACE_UNDEFINED);
        $core->setImageColorspace(\Imagick::COLORSPACE_UNDEFINED);

        $size = new Size($width, $height);

        // build image
        $image = new \Intervention\Image\Image(new self, $core, $size);

        return $image;
    }

    /**
     * Reads given string into color object
     *
     * @param  string $value
     * @return AbstractColor
     */
    public function parseColor($value)
    {
        return new Color($value);
    }

    /**
     * Checks if core module installation is available
     *
     * @return boolean
     */
    protected function coreAvailable()
    {
        return (extension_loaded('imagick') && class_exists('Imagick'));
    }
}
