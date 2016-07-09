<?php

namespace Intervention\Image\Imagick\Commands;

use Intervention\Image\Comparison;
use Intervention\Image\Imagick\Color;

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
        $otherImage = $this->argument(0)->required()->value();
        $threshold = $this->argument(1)->value(20);
        $highlightColor = $this->argument(2)->value(new Color('rgb(255,0,0)'));
        $resizeCanvas = $this->argument(3)->value(true);
        $metric = $this->argument(4)->value(1);

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

        $one = new \Imagick();
        $one->setOption('fuzz', $threshold.'%');
        $one->setOption('highlight-color', $highlightColor->getRgba());
        $one->readImageBlob($image->getCore()->getImageBlob());

        $compare = $one->compareImages($otherImage->getCore(), $metric);

        $diffImage->setCore($compare[0]);

        $this->setOutput(new Comparison($metric, $compare[1], $diffImage));

        return true;
    }
}
