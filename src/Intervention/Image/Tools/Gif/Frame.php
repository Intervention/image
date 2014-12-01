<?php

namespace Intervention\Image\Tools\Gif;

class Frame
{
    /**
     * Determines if property is already set
     *
     * @param  string  $name
     * @return boolean
     */
    public function hasProperty($name)
    {
        return property_exists($this, $name);
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
     * Returns delay of current instance
     *
     * @return int
     */
    public function getDelay()
    {
        if (property_exists($this, 'graphicsControlExtension') && $this->graphicsControlExtension) {
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
        if (property_exists($this, 'graphicsControlExtension') && $this->graphicsControlExtension) {
            $byte = substr($this->graphicsControlExtension, 1, 1);
            $byte = unpack('C', $byte)[1];
            $bit = $byte & bindec('00000001');

            return (bool) $bit;
        }

        return false;
    }

    /**
     * Returns index byte of transparent color
     *
     * @return string
     */
    public function getTransparentColorIndex()
    {
        if (property_exists($this, 'graphicsControlExtension') && $this->graphicsControlExtension) {
            return substr($this->graphicsControlExtension, 4, 1);
        }

        return false;        
    }

    /**
     * Determines if current frame is saved as interlaced
     *
     * @return boolean
     */
    public function isInterlaced()
    {
        return $this->interlaced;
    }
}
