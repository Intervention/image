<?php

namespace Intervention\Image\Gd\Commands;

use Intervention\Image\Comparison;
use Intervention\Image\Gd\Color;

class CompareCommand extends \Intervention\Image\Commands\AbstractCommand
{
    /**
     * Compare two images and produce a diff
     *
     * @param  \Intervention\Image\Image $image1
     * @return boolean
     */
    public function execute($image)
    {
        $image = clone $image;
        $diffImage = clone $image;
        $otherImage = clone $this->argument(0)->required()->value();
        $threshold = $this->argument(1)->value(20);
        $highlightColor = $this->argument(2)->value(new Color('rgb(255,0,0)'));
        $resizeCanvas = $this->argument(3)->value(true);

        $width = $image->width();
        $height = $image->height();
        $otherWidth = $otherImage->width();
        $otherHeight = $otherImage->height();
        $outputWidth = max($width, $otherWidth);
        $outputHeight = max($height, $otherHeight);

        if ($resizeCanvas) {
            $image->resizeCanvas($outputWidth, $outputHeight, 'top-left');
            $otherImage->resizeCanvas($outputWidth, $outputHeight, 'top-left');
        } elseif ($width != $otherWidth or $height != $otherHeight) {
            throw new \Intervention\Image\Exception\InvalidArgumentException(
                'The two images are different sizes and you passed $resizeCanvas = false.'
            );
        }

        $diffImageCore = $diffImage->getCore();
        $imageCore = $image->getCore();
        $otherImageCore = $otherImage->getCore();
        $highlightColor = imagecolorallocate($diffImageCore, $highlightColor->r, $highlightColor->g, $highlightColor->b);

        $diffPixelCount = 0;
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {

                $index1 = imagecolorat($imageCore, $x, $y);
                $rgba1 = imagecolorsforindex($imageCore, $index1);

                $index2 = imagecolorat($otherImageCore, $x, $y);
                $rgba2 = imagecolorsforindex($otherImageCore, $index2);

                if (! $this->compareColors($rgba1, $rgba2, $threshold)) {
                    $diffPixelCount++;
                    imagesetpixel($diffImageCore, $x, $y, $highlightColor);
                }
            }
        }

        $diffImage->setCore($diffImageCore);

        $this->setOutput(new Comparison(1, $diffPixelCount, $diffImage));

        return true;
    }

    /**
     * Check if two colours are similar enough to pass our diff threshold.
     *
     * @param array $rgba1
     * @param array $rgba2
     * @param int   $threshold
     *
     * @return bool
     */
    protected function compareColors(array $rgba1, array $rgba2, $threshold = 0)
    {
        $red   = abs($rgba2['red'] - $rgba1['red']);
        $green = abs($rgba2['green'] - $rgba1['green']);
        $blue  = abs($rgba2['blue'] - $rgba1['blue']);

        $diffPercentage = ($red + $green + $blue) * 100 / (255 * 3);

        return $diffPercentage <= $threshold;
    }
}
