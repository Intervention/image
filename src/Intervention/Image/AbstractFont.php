<?php

namespace Intervention\Image;

abstract class AbstractFont
{
    /**
     * Text to be written
     *
     * @var String
     */
    public $text;

    /**
     * Text size in pixels
     *
     * @var integer
     */
    public $size = 12;

    /**
     * Color of the text
     *
     * @var mixed
     */
    public $color = '#000000';

    /**
     * Color of the text stroke
     *
     * @var mixed
     */
    public $stroke_color = '#000000';

    /**
     * Width of the stroke
     *
     * @var float
     */
    public $stroke_width = 0;

    /**
     * Rotation angle of the text
     *
     * @var integer
     */
    public $angle = 0;

    /**
     * Horizontal alignment of the text
     *
     * @var String
     */
    public $align;

    /**
     * Vertical alignment of the text
     *
     * @var String
     */
    public $valign;

    /**
     * Path to TTF or GD library internal font file of the text
     *
     * @var mixed
     */
    public $file;

    /**
     * Draws font to given image on given position
     *
     * @param  Image   $image
     * @param  integer $posx
     * @param  integer $posy
     * @return boolean
     */
    abstract public function applyToImage(Image $image, $posx = 0, $posy = 0);

    /**
     * Create a new instance of Font
     *
     * @param Strinf $text Text to be written
     */
    public function __construct($text = null)
    {
        $this->text = $text;
    }

    /**
     * Set text to be written
     *
     * @param  String $text
     * @return void
     */
    public function text($text)
    {
        $this->text = $text;
    }

    /**
     * Get text to be written
     *
     * @return String
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set font size in pixels
     *
     * @param  integer $size
     * @return void
     */
    public function size($size)
    {
        $this->size = $size;
    }

    /**
     * Get font size in pixels
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set stroke width in pixels
     *
     * @param  float $width
     * @return void
     */
    public function strokeWidth($width)
    {
        $this->stroke_width = $width;
    }

    /**
     * Get stroke width in pixels
     *
     * @return float
     */
    public function getStrokeWidth()
    {
        return $this->stroke_width;
    }

    /**
     * Set color of text to be written
     *
     * @param  mixed $color
     * @return void
     */
    public function color($color)
    {
        $this->color = $color;
    }

    /**
     * Get color of text
     *
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set stroke color of text to be written
     *
     * @param  mixed $color
     * @return void
     */
    public function strokeColor($color)
    {
        $this->stroke_color = $color;
    }

    /**
     * Get stroke color of text
     *
     * @return mixed
     */
    public function getStrokeColor()
    {
        return $this->stroke_color;
    }

    /**
     * Set rotation angle of text
     *
     * @param  integer $angle
     * @return void
     */
    public function angle($angle)
    {
        $this->angle = $angle;
    }

    /**
     * Get rotation angle of text
     *
     * @return integer
     */
    public function getAngle()
    {
        return $this->angle;
    }

    /**
     * Set horizontal text alignment
     *
     * @param  string $align
     * @return void
     */
    public function align($align)
    {
        $this->align = $align;
    }

    /**
     * Get horizontal text alignment
     *
     * @return string
     */
    public function getAlign()
    {
        return $this->align;
    }

    /**
     * Set vertical text alignment
     *
     * @param  string $valign
     * @return void
     */
    public function valign($valign)
    {
        $this->valign = $valign;
    }

    /**
     * Get vertical text alignment
     *
     * @return string
     */
    public function getValign()
    {
        return $this->valign;
    }

    /**
     * Set path to font file
     *
     * @param  string $align
     * @return void
     */
    public function file($file)
    {
        $this->file = $file;
    }

    /**
     * Get path to font file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Checks if current font has access to an applicable font file
     *
     * @return boolean
     */
    protected function hasApplicableFontFile()
    {
        if (is_string($this->file)) {
            return file_exists($this->file);
        }

        return false;
    }

    /**
     * Counts lines of text to be written
     *
     * @return integer
     */
    public function countLines()
    {
        return count(explode(PHP_EOL, $this->text));
    }
}
