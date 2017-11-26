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
    public $color = '000000';

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
     * Color of the text stroke
     *
     * @var mixed
     */
    public $strokeColor = 'FFFFFF';

    /**
     * Width of the stroke
     *
     * @var float
     */
    public $strokeWidth = 0;

    /**
     * Draws font to given image on given position
     *
     * @param  Image $image
     * @param  integer $posx
     * @param  integer $posy
     * @return boolean
     */
    abstract public function applyToImage(Image $image, $posx = 0, $posy = 0);

    /**
     * Calculates bounding box of current font setting
     *
     * @return array
     */
    abstract public function getBoxSize();

    /**
     * Create a new instance of Font
     *
     * @param string $text Text to be written
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

        return $this;
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

        return $this;
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
     * Set color of text to be written
     *
     * @param  mixed $color
     * @return void
     */
    public function color($color)
    {
        $this->color = $color;

        return $this;
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
     * Set rotation angle of text
     *
     * @param  integer $angle
     * @return void
     */
    public function angle($angle)
    {
        $this->angle = $angle;

        return $this;
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

        return $this;
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

        return $this;
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
     * @param  string $file
     * @return static
     */
    public function file($file)
    {
        $this->file = $file;

        return $this;
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
     * Set stroke width in pixels
     *
     * @param  float $width
     * @return void
     */
    public function strokeWidth($width)
    {
        $this->strokeWidth = $width;
    }

    /**
     * Get stroke width in pixels
     *
     * @return float
     */
    public function getStrokeWidth()
    {
        return $this->strokeWidth;
    }

    /**
     * Set stroke color of text to be written
     *
     * @param  mixed $color
     * @return void
     */
    public function strokeColor($color)
    {
        $this->strokeColor = $color;
    }

    /**
     * Get stroke color of text
     *
     * @return mixed
     */
    public function getStrokeColor()
    {
        return $this->strokeColor;
    }

    /**
     * Counts lines of text to be written
     *
     * @return integer
     */
    public function countLines()
    {
        return count(explode(chr(10), $this->text));
    }

    /**
     * @param integer  $posX
     * @param integer  $posY
     * @param callable $function
     */
    protected function strokeDrawLoop($posX, $posY, callable $function)
    {
        for ($offsetX = $posX - $this->strokeWidth; $offsetX <= $posX + $this->strokeWidth; $offsetX++) {
            for ($offsetY = $posY - $this->strokeWidth; $offsetY <= $posY + $this->strokeWidth; $offsetY++) {
                $function($offsetX, $offsetY);
            }
        }
    }

}
