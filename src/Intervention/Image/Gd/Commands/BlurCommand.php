<?php

namespace Intervention\Image\Gd\Commands;

class BlurCommand extends \Intervention\Image\Commands\AbstractCommand
{
    /**
     * Apply blur effect on image frames
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        $amount = $this->argument(0)->between(0, 100)->value(1);

        foreach ($image as $frame) {
            $this->applyBlur($frame->getCore(), $amount);
        }

        return true;
    }

    /**
     * Apply blur effect on GD resource
     *
     * @param  resource $resource
     * @param  integer  $amount
     * @return void
     */
    private function applyBlur($resource, $amount)
    {
        for ($i=0; $i < intval($amount); $i++) {
            imagefilter($resource, IMG_FILTER_GAUSSIAN_BLUR);
        }
    }
}
