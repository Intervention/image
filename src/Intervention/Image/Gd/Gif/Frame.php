<?php

namespace Intervention\Image\Gd\Gif;

class Frame
{
    const DISPOSAL_METHOD_LEAVE = 1;
    const DISPOSAL_METHOD_BACKGROUND = 2;
    const DISPOSAL_METHOD_PREVIOUS = 3;

    public $graphicsControlExtension;
    public $imageDescriptor;
    public $imageData;
    public $localColorTable;
    public $transparentColorIndex;
    public $disposalMethod;
    public $interlaced;
    public $offset;
    public $size;
    public $delay;

    /**
     * Determines if property is already set
     *
     * @param  string  $name
     * @return boolean
     */
    public function propertyIsSet($name)
    {
        return property_exists($this, $name) && ($this->{$name} !== null);
    }

    /**
     * Creates and sets property with given value
     *
     * @param string $name
     * @param Frame
     */
    public function setProperty($name, $value)
    {
        $this->{$name} = $value;

        return $this;
    }

    /**
     * Sets image data of fram
     *
     * @param string $value
     */
    public function setImageData($value)
    {
        $this->imageData = $value;

        return $this;
    }

    /**
     * Returns image data
     *
     * @return string
     */
    public function getImageData()
    {
        return $this->imageData;
    }

    /**
     * Determines if instance has local color table
     *
     * @return boolean
     */
    public function hasLocalColorTable()
    {
        return ! is_null($this->localColorTable);
    }

    /**
     * Returns local color table data of instance
     *
     * @return string
     */
    public function getLocalColorTable()
    {
        return $this->localColorTable;
    }

    /**
     * Sets local color table of frame
     *
     * @param string $value
     */
    public function setLocalColorTable($value)
    {
        $this->localColorTable = $value;

        return $this;
    }

    /**
     * Returns delay of frame
     *
     * @return integer
     */
    public function getDelay()
    {
        return $this->propertyIsSet('delay')
            ? $this->delay
            : $this->decodeDelay();
    }

    /**
     * Sets delay of frame
     *
     * @param integer $delay
     */
    public function setDelay($delay)
    {
        $this->delay = $delay;

        return $this;
    }

    /**
     * Returns delay of current instance
     *
     * @return int
     */
    public function decodeDelay()
    {
        if ($this->graphicsControlExtension) {
            $byte = substr($this->graphicsControlExtension, 2, 2);
            return (int) unpack('v', $byte)[1];
        }

        return false;
    }

    /**
     * Determines if instance has transparent colors
     *
     * @return boolean
     */
    public function hasTransparentColor()
    {
        if ($this->graphicsControlExtension) {
            $byte = substr($this->graphicsControlExtension, 1, 1);
            $byte = unpack('C', $byte)[1];
            $bit = $byte & bindec('00000001');

            return (bool) $bit;
        }

        return false;
    }

    /**
     * Returns transparent color index of frame
     *
     * @return integer
     */
    public function getTransparentColorIndex()
    {
        return $this->propertyIsSet('transparentColorIndex') 
            ? $this->transparentColorIndex 
            : $this->decodeTransparentColorIndex();
    }

    /**
     * Sets transparent color index of frame
     *
     * @param integer $value
     */
    public function setTransparentColorIndex($value)
    {
        $this->transparentColorIndex = $value;

        return $this;
    }

    /**
     * Returns index byte of transparent color
     *
     * @return string
     */
    public function decodeTransparentColorIndex()
    {
        if ($this->graphicsControlExtension) {
            return substr($this->graphicsControlExtension, 4, 1);
        }

        return false;        
    }

    /**
     * Returns disposal method of frame
     *
     * @return integer
     */
    public function getDisposalMethod()
    {
        return $this->propertyIsSet('disposalMethod') 
            ? $this->disposalMethod 
            : $this->decodeDisposalMethod();
    }

    /**
     * Defines disposal method of frame
     *
     * @param integer $value
     */
    public function setDisposalMethod($value)
    {
        $this->disposalMethod = $value;

        return $this;
    }

    /**
     * Decodes disposal method of frame
     *
     * @return integer
     */
    public function decodeDisposalMethod()
    {
        if ($this->graphicsControlExtension) {
            $byte = substr($this->graphicsControlExtension, 1, 1);
            $byte = unpack('C', $byte)[1];
            $method = $byte >> 2 & bindec('00000111');

            return $method;
        }

        return 0;
    }

    /**
     * Decodes frame width
     *
     * @return integer
     */
    public function decodeWidth()
    {
        if ($this->imageDescriptor) {
            return (int) unpack('v', 
                substr($this->imageDescriptor, 4, 2)
            )[1];
        }

        return false;
    }

    /**
     * Decodes frame height
     *
     * @return integer
     */
    public function decodeHeight()
    {
        if ($this->imageDescriptor) {
            return (int) unpack('v', 
                substr($this->imageDescriptor, 6, 2)
            )[1];
        }

        return false;
    }

    /**
     * Decodes width and height of frame to size object
     *
     * @return StdClass
     */
    public function getSize()
    {
        $size = new \StdClass;
        $size->width = $this->decodeWidth();
        $size->height = $this->decodeHeight();

        return $size;
    }

    /**
     * Decodes left offset of frame
     *
     * @return integer
     */
    public function decodeOffsetLeft()
    {
        if ($this->imageDescriptor) {
            return (int) unpack('v', 
                substr($this->imageDescriptor, 0, 2)
            )[1];
        }

        return false;
    }

    /**
     * Decodes top offset of frame
     *
     * @return integer
     */
    public function decodeOffsetTop()
    {
        if ($this->imageDescriptor) {
            return (int) unpack('v', 
                substr($this->imageDescriptor, 2, 2)
            )[1];
        }

        return false;
    }

    /**
     * Decodes offsets into object
     *
     * @return StdClass
     */
    public function getOffset()
    {
        $offset = new \StdClass;
        $offset->left = $this->decodeOffsetLeft();
        $offset->top = $this->decodeOffsetTop();

        return $offset;
    }

    /**
     * Sets frame offset
     *
     * @param integer $left
     * @param integer $top
     */
    public function setOffset($left, $top)
    {
        $offset = new \StdClass;
        $offset->left = $left;
        $offset->top = $top;
        $this->offset = $offset;

        return $this;
    }

    /**
     * Mark frame as interlaced
     *
     * @param boolean $flag
     */
    public function setInterlaced($flag)
    {
        $this->interlaced = $flag;

        return $this;
    }

    /**
     * Returns interlaced mode
     *
     * @return boolean
     */
    public function getInterlaced()
    {
        return $this->propertyIsSet('interlaced') ? $this->interlaced : $this->decodeInterlaced();
    }

    /**
     * Determines if current frame is saved as interlaced
     *
     * @return boolean
     */
    public function isInterlaced()
    {
        return (boolean) $this->getInterlaced();
    }
}
