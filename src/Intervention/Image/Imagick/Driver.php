<?php

namespace Intervention\Image\Imagick;

class Driver extends \Intervention\Image\AbstractDriver
{
    /**
     * Creates new instance of driver
     *
     * @param Decoder $decoder
     * @param Encoder $encoder
     */
    public function __construct(Decoder $decoder = null, Encoder $encoder = null)
    {
        if ( ! $this->coreAvailable()) {
            throw new \Intervention\Image\Exception\NotSupportedException(
                "ImageMagick module not available with this PHP installation."
            );
        }

        $this->decoder = $decoder ? $decoder : new Decoder;
        $this->encoder = $encoder ? $encoder : new Encoder;
    }

    /**
     * Creates new image instance
     *
     * @param  integer $width
     * @param  integer $height
     * @param  string  $background
     * @return \Intervention\Image\Image
     */
    public function newImage($width, $height, $background = null)
    {
        $background = new Color($background);

        // create empty core
        $core = new \Imagick;
        $core->newImage($width, $height, $background->getPixel(), 'png');
        $core->setType(\Imagick::IMGTYPE_UNDEFINED);
        $core->setImageType(\Imagick::IMGTYPE_UNDEFINED);
        $core->setColorspace(\Imagick::COLORSPACE_UNDEFINED);

        // build image
        $image = new \Intervention\Image\Image(new static, $core);

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
