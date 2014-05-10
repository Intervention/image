<?php

namespace Intervention\Image\Gd;

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
        // create empty resource
        $core = imagecreatetruecolor($width, $height);
        $size = new Size($width, $height);
        $image = new \Intervention\Image\Image(new self, $core, $size);

        // set background color
        $background = new Color($background);
        imagefill($image->getCore(), 0, 0, $background->getInt());

        return $image;
    }

    public function parseColor($value)
    {
        return new Color($value);
    }
}
