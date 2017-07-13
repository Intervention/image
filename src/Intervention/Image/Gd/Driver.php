<?php

namespace Intervention\Image\Gd;

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
                "GD Library extension not available with this PHP installation."
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
     * @param  mixed   $background
     * @return \Intervention\Image\Image
     */
    public function newImage($width, $height, $background = null)
    {
        // create empty resource
        $core = imagecreatetruecolor($width, $height);
        $image = new \Intervention\Image\Image(new static, $core);

        // set background color
        $background = new Color($background);
        imagefill($image->getCore(), 0, 0, $background->getInt());

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
        return (extension_loaded('gd') && function_exists('gd_info'));
    }

    /**
     * Returns clone of given core
     *
     * @return mixed
     */
    public function cloneCore($core)
    {
        if (imageistruecolor($core)) {
            return $this->cloneTrueColorCore($core);
        }

        return $this->cloneColorPaletteCore($core);
    }

    /**
     * Returns clone of a true color core
     *
     * @return mixed
     */
    protected function cloneTrueColorCore($core)
    {
        $width = imagesx($core);
        $height = imagesy($core);
        $clone = imagecreatetruecolor($width, $height);
        imagealphablending($clone, false);
        imagesavealpha($clone, true);

        imagecopy($clone, $core, 0, 0, 0, 0, $width, $height);

        return $clone;
    }

    /**
     * Returns clone of a core with a color palette
     *
     * @return mixed
     */
    protected function cloneColorPaletteCore($core)
    {
        $width = imagesx($core);
        $height = imagesy($core);
        $clone = imagecreate($width, $height);
        imagesavealpha($clone, true);

        $rgb = imagecolorsforindex($core, imagecolortransparent($core));
        $alpha = imagecolorallocatealpha(
            $clone,
            $rgb['red'],
            $rgb['green'],
            $rgb['blue'],
            $rgb['alpha']
        );
        imagefill($clone, 0, 0, $alpha);

        imagecopy($clone, $core, 0, 0, 0, 0, $width, $height);

        return $clone;
    }
}
