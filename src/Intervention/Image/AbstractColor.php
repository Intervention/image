<?php

namespace Intervention\Image;

abstract class AbstractColor
{
    abstract public function initFromInteger($value);
    abstract public function initFromArray($value);
    abstract public function initFromString($value);
    abstract public function initFromObject($value);
    abstract public function initFromRgb($r, $g, $b);
    abstract public function initFromRgba($r, $g, $b, $a);
    abstract public function getInt();
    abstract public function getHex($prefix);
    abstract public function getArray();
    abstract public function getRgba();
    abstract public function differs(AbstractColor $color, $tolerance = 0);

    public function __construct($value = null)
    {
        $this->parse($value);
    }

    public function parse($value)
    {
        switch (true) {

            case is_string($value):
                $this->initFromString($value);
                break;

            case is_int($value):
                $this->initFromInteger($value);
                break;

            case is_array($value):
                $this->initFromArray($value);
                break;

            case is_object($value):
                $this->initFromObject($value);
                break;

            case is_null($value):
                $this->initFromArray(array(0, 0, 0, 0));
                break;
            
            default:
                throw new \Intervention\Image\Exception\NotReadableException(
                    "Color format ({$value}) cannot be read."
                );
                break;
        }

        return $this;
    }

    public function format($type)
    {
        switch (strtolower($type)) {
            
            case 'rgba':
                return $this->getRgba();
                break;

            case 'hex':
                return $this->getHex('#');
                break;

            case 'int':
            case 'integer':
                return $this->getInt();
                break;
            
            case 'array':
                return $this->getArray();
                break;

            case 'obj':
            case 'object':
                return $this;
                break;

            default:
                throw new \Intervention\Image\Exception\NotSupportedException(
                    "Color format ({$type}) is not supported."
                );
                break;
        }
    }

    protected function rgbaFromString($value)
    {
        $result = false;

        // parse color string in hexidecimal format like #cccccc or cccccc or ccc
        $hexPattern = '/^#?([a-f0-9]{1,2})([a-f0-9]{1,2})([a-f0-9]{1,2})$/i';

        // parse color string in format rgb(140, 140, 140)
        $rgbPattern = '/^rgb ?\(([0-9]{1,3}), ?([0-9]{1,3}), ?([0-9]{1,3})\)$/i';

        // parse color string in format rgba(255, 0, 0, 0.5)
        $rgbaPattern = '/^rgba ?\(([0-9]{1,3}), ?([0-9]{1,3}), ?([0-9]{1,3}), ?([0-9.]{1,4})\)$/i';

        if (preg_match($hexPattern, $value, $matches)) {
            $result = array();
            $result[0] = strlen($matches[1]) == '1' ? hexdec($matches[1].$matches[1]) : hexdec($matches[1]);
            $result[1] = strlen($matches[2]) == '1' ? hexdec($matches[2].$matches[2]) : hexdec($matches[2]);
            $result[2] = strlen($matches[3]) == '1' ? hexdec($matches[3].$matches[3]) : hexdec($matches[3]);
            $result[3] = 1;
        } elseif (preg_match($rgbPattern, $value, $matches)) {
            $result = array();
            $result[0] = ($matches[1] >= 0 && $matches[1] <= 255) ? intval($matches[1]) : 0;
            $result[1] = ($matches[2] >= 0 && $matches[2] <= 255) ? intval($matches[2]) : 0;
            $result[2] = ($matches[3] >= 0 && $matches[3] <= 255) ? intval($matches[3]) : 0;
            $result[3] = 1;
        } elseif (preg_match($rgbaPattern, $value, $matches)) {
            $result = array();
            $result[0] = ($matches[1] >= 0 && $matches[1] <= 255) ? intval($matches[1]) : 0;
            $result[1] = ($matches[2] >= 0 && $matches[2] <= 255) ? intval($matches[2]) : 0;
            $result[2] = ($matches[3] >= 0 && $matches[3] <= 255) ? intval($matches[3]) : 0;
            $result[3] = ($matches[4] >= 0 && $matches[4] <= 1) ? $matches[4] : 0;
        } else {
            throw new \Intervention\Image\Exception\NotReadableException(
                "Unable to read color ({$value})."
            );
        }

        return $result;
    }
}
