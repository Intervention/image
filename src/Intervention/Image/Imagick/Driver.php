<?php

namespace Intervention\Image\Imagick;

use \Intervention\Image\Size;

class Driver extends \Intervention\Image\AbstractDriver
{
    public $source;
    public $encoder;

	public function __construct(Source $source = null, Encoder $encoder = null)
	{
        $this->source = $source ? $source : new Source;
	    $this->encoder = $encoder ? $encoder : new Encoder;
	}

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

    public function parseColor($value)
    {
        return new Color($value);
    }
}
