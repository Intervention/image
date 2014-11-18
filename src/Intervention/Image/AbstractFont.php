<?php

namespace Intervention\Image;

abstract class AbstractFont
{
    /**
     * Security padding to prevent text being cut off
     */
    const PADDING = 10;

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
     * Line height of the text
     *
     * @var float
     */
    public $lineHeight = 1;

    /**
     * Textbox
     *
     * @var Size
     */
    public $box;

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
     * Calculate boxsize including own features
     *
     * @param  string $text
     * @return \Intervention\Image\Size
     */
    abstract public function getBoxSize($text = null);

    /**
     * Get raw boxsize without any non-core features
     *
     * @param  string $text
     * @return \Intervention\Image\Size
     */
    abstract protected function getCoreBoxSize($text = null);

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
     * Set line-height
     *
     * @param  float $height
     * @return void
     */
    public function lineHeight($lineHeight)
    {
        $this->lineHeight = $lineHeight;
    }

    /**
     * Get line-height of instance
     *
     * @return float
     */
    public function getLineHeight()
    {
        return $this->lineHeight;
    }

    /**
     * Set size of boxed text
     *
     * @param  integer $width
     * @param  integer $height
     * @return void
     */
    public function box($width, $height)
    {
        $this->box = new Size($width, $height);
    }

    /**
     * Get width of textbox
     *
     * @return integer
     */
    public function getBox()
    {
        return $this->box;
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
        return count($this->getLines());
    }

    /**
     * Get array of lines to be written
     *
     * @return array
     */
    public function getLines($text = null)
    {
        $text = is_null($text) ? $this->text : $text;

        return explode(PHP_EOL, $text);
    }

    /**
     * Get array of words to be written
     *
     * @return array
     */
    public function getWords()
    {
        return explode(' ', $this->text);
    }

    /**
     * Determines if text has defined box size
     *
     * @return boolean
     */
    protected function isBoxed()
    {
        return is_a($this->box, 'Intervention\Image\Size');
    }

    /**
     * Returns formated text according to box settings
     *
     * @return string
     */
    protected function format()
    {
        if ($this->isBoxed()) {

            $line = array();
            $lines = array();

            foreach ($this->getWords() as $word) {
                
                $linesize = $this->getCoreBoxSize(
                    implode(' ', array_merge($line, array(trim($word))))
                );

                if ($linesize->getWidth() <= $this->box->getWidth()) {
                    $line[] = trim($word);
                } else {
                    $lines[] = implode(' ', $line);
                    $line = array(trim($word));
                }
            }

            $lines[] = trim(implode(' ', $line));

            return implode(PHP_EOL, $lines);
        }
    }
}
