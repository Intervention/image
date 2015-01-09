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

    public function setImageData($value)
    {
        $this->imageData = $value;

        return $this;
    }

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

    public function setLocalColorTable($value)
    {
        $this->localColorTable = $value;

        return $this;
    }

    public function getDelay()
    {
        return $this->propertyIsSet('delay')
            ? $this->delay
            : $this->decodeDelay();
    }

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

    public function getTransparentColorIndex()
    {
        return $this->propertyIsSet('transparentColorIndex') 
            ? $this->transparentColorIndex 
            : $this->decodeTransparentColorIndex();
    }

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

    public function getDisposalMethod()
    {
        return $this->propertyIsSet('disposalMethod') 
            ? $this->disposalMethod 
            : $this->decodeDisposalMethod();
    }

    public function setDisposalMethod($value)
    {
        $this->disposalMethod = $value;

        return $this;
    }

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

    public function decodeWidth()
    {
        if ($this->imageDescriptor) {
            return (int) unpack('v', 
                substr($this->imageDescriptor, 4, 2)
            )[1];
        }

        return false;
    }

    public function decodeHeight()
    {
        if ($this->imageDescriptor) {
            return (int) unpack('v', 
                substr($this->imageDescriptor, 6, 2)
            )[1];
        }

        return false;
    }

    public function getSize()
    {
        $size = new \StdClass;
        $size->width = $this->decodeWidth();
        $size->height = $this->decodeHeight();

        return $size;
    }

    public function decodeOffsetLeft()
    {
        if ($this->imageDescriptor) {
            return (int) unpack('v', 
                substr($this->imageDescriptor, 0, 2)
            )[1];
        }

        return false;
    }

    public function decodeOffsetTop()
    {
        if ($this->imageDescriptor) {
            return (int) unpack('v', 
                substr($this->imageDescriptor, 2, 2)
            )[1];
        }

        return false;
    }

    public function getOffset()
    {
        $offset = new \StdClass;
        $offset->left = $this->decodeOffsetLeft();
        $offset->top = $this->decodeOffsetTop();

        return $offset;
    }

    public function setOffset($left, $top)
    {
        $offset = new \StdClass;
        $offset->left = $left;
        $offset->top = $top;
        $this->offset = $offset;

        return $this;
    }

    public function setInterlaced(boolean $flag)
    {
        $this->interlaced = $flag;

        return $this;
    }

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
