<?php

namespace Intervention\Image\Gd\Commands;

use Intervention\Image\Gd\Color;

class ColorsCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $number = $this->argument(0)->min(1)->value(5);
        $accuracy = $this->argument(1)->between(1, 100)->value(100);
        $format = $this->argument(2)->type('string')->value('hex');
        
        $output = array();
        $colors = array();

        $size = $image->getSize();
        $step_x = round($size->width / ($size->width / 100 * $accuracy));
        $step_y = round($size->height / ($size->height / 100 * $accuracy));

        // read colors from image
        for ($x=0; $x < $size->width; $x=$x+$step_x) { 
            for ($y=0; $y < $size->height; $y=$y+$step_y) { 
                $color = imagecolorat($image->getCore(), $x, $y);
                if (isset($colors[$color])) {
                    $colors[$color]++;
                } else {
                    $colors[$color] = 1;
                }
            }
        }

        // sort colors by color-value
        ksort($colors);        

        // consolidate colors
        $compare_color = null;

        foreach ($colors as $key => $value) {
            $color = new Color($key);
            if ( ! is_null($compare_color)) {
                if ( ! $color->differs($compare_color, 80)) {
                    unset($colors[$key]);
                } else {
                    $compare_color = $color;
                }
            } else {
                $compare_color = $color;
            }
        }


        // sort colors by appearance
        arsort($colors);

        // slice array
        $colors = array_slice($colors, 0, $number, true);

        // format colors
        foreach ($colors as $color => $count) {
            $color = new Color($color);
            $output[] = $color->format($format);
        }

        $this->setOutput($output);
    }
}
