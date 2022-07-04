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
     * @var int
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
     * @var int
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
     * Space between text characters
     *
     * @var float
     */
    public $kerning = 0;

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
     * @param  int     $posx
     * @param  int     $posy
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
     * @param String $text Text to be written
     */
    public function __construct($text = null)
    {
        $this->text = $text;
    }

    /**
     * Set text to be written
     *
     * @param  String $text
     * @return self
     */
    final public function text($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text to be written
     *
     * @return String
     */
    final public function getText()
    {
        return $this->text;
    }

    /**
     * Set font size in pixels
     *
     * @param  int $size
     * @return self
     */
    final public function size($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get font size in pixels
     *
     * @return int
     */
    final public function getSize()
    {
        return $this->size;
    }

    /**
     * Set color of text to be written
     *
     * @param  mixed $color
     * @return self
     */
    final public function color($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color of text
     *
     * @return mixed
     */
    final public function getColor()
    {
        return $this->color;
    }

    /**
     * Set rotation angle of text
     *
     * @param  int $angle
     * @return self
     */
    final public function angle($angle)
    {
        $this->angle = $angle;

        return $this;
    }

    /**
     * Get rotation angle of text
     *
     * @return int
     */
    final public function getAngle()
    {
        return $this->angle;
    }

    /**
     * Set horizontal text alignment
     *
     * @param  string $align
     * @return self
     */
    final public function align($align)
    {
        $this->align = $align;

        return $this;
    }

    /**
     * Get horizontal text alignment
     *
     * @return string
     */
    final public function getAlign()
    {
        return $this->align;
    }

    /**
     * Set vertical text alignment
     *
     * @param  string $valign
     * @return self
     */
    final public function valign($valign)
    {
        $this->valign = $valign;

        return $this;
    }

    /**
     * Get vertical text alignment
     *
     * @return string
     */
    final public function getValign()
    {
        return $this->valign;
    }

    /**
     * Set text kerning
     *
     * @param  string $kerning
     * @return void
     */
    final public function kerning($kerning)
    {
        $this->kerning = $kerning;
    }

    /**
     * Get kerning
     *
     * @return float
     */
    final public function getKerning()
    {
        return $this->kerning;
    }

    /**
     * Set path to font file
     *
     * @param  string $file
     * @return self
     */
    final public function file($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get path to font file
     *
     * @return string
     */
    final public function getFile()
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
     * @return int
     */
    final public function countLines()
    {
        return count(explode(PHP_EOL, $this->text));
    }
}
