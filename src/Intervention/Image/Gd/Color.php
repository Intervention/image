<?php

namespace Intervention\Image\Gd;

use Intervention\Image\AbstractColor;

class Color extends AbstractColor
{
    public $r;
    public $g;
    public $b;
    public $a;

    public function initFromInteger($value)
    {
        $this->a = ($value >> 24) & 0xFF;
        $this->r = ($value >> 16) & 0xFF;
        $this->g = ($value >> 8) & 0xFF;
        $this->b = $value & 0xFF;
    }

    public function initFromArray($array)
    {
        $array = array_values($array);

        if (count($array) == 4) {

            // color array with alpha value
            list($r, $g, $b, $a) = $array;
            $this->a = $this->alpha2gd($a);

        } elseif (count($array) == 3) {

            // color array without alpha value
            list($r, $g, $b) = $array;
            $this->a = 0;

        }

        $this->r = $r;
        $this->g = $g;
        $this->b = $b;
    }

    public function initFromString($value)
    {
        if ($color = $this->rgbaFromString($value)) {
            $this->r = $color[0];
            $this->g = $color[1];
            $this->b = $color[2];
            $this->a = $this->alpha2gd($color[3]);
        }
    }

    public function initFromRgb($r, $g, $b)
    {
        $this->r = intval($r);
        $this->g = intval($g);
        $this->b = intval($b);
        $this->a = 0;
    }

    public function initFromRgba($r, $g, $b, $a = 1)
    {
        $this->r = intval($r);
        $this->g = intval($g);
        $this->b = intval($b);
        $this->a = $this->alpha2gd($a);
    }

    public function initFromObject($value)
    {
        throw new \Exception('Not available');
    }

    public function getInt()
    {
        return ($this->a << 24) + ($this->r << 16) + ($this->g << 8) + $this->b;
    }

    public function getHex($prefix = '')
    {
        return sprintf('%s%02x%02x%02x', $prefix, $this->r, $this->g, $this->b);
    }

    public function getArray()
    {
        return array($this->r, $this->g, $this->b, round(1 - $this->a / 127, 2));
    }

    public function getRgba()
    {
        return sprintf('rgba(%d, %d, %d, %.2f)', $this->r, $this->g, $this->b, round(1 - $this->a / 127, 2));
    }

    public function differs(AbstractColor $color, $tolerance = 0)
    {
        $color_tolerance = round($tolerance * 2.55);
        $alpha_tolerance = round($tolerance * 1.27);

        $delta = array(
            'r' => abs($color->r - $this->r),
            'g' => abs($color->g - $this->g),
            'b' => abs($color->b - $this->b),
            'a' => abs($color->a - $this->a)
        );

        return (
            $delta['r'] > $color_tolerance or
            $delta['g'] > $color_tolerance or
            $delta['b'] > $color_tolerance or
            $delta['a'] > $alpha_tolerance
        );
    }

    /**
     * Convert rgba alpha (0-1) value to gd value (0-127)
     *
     * @param  float $input
     * @return int
     */
    private function alpha2gd($input)
    {
        $range_input = range(1, 0, 1/127);
        $range_output = range(0, 127);

        foreach ($range_input as $key => $value) {
            if ($value <= $input) {
                return $range_output[$key];
            }
        }

        return 127;
    }
}
