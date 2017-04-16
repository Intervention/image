<?php

namespace Intervention\Image\Imagick\Commands;

class SamplingFactorCommand extends \Intervention\Image\Commands\AbstractCommand
{
    /**
     * Set the sampling factors to be used by the JPEG encoder for chroma downsampling
     * Use "1x1" to disable chroma subsampling
     *
     * @param  Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        $factor = $this->argument(0)->type('string')->required()->value();

        return $image->getCore()->setSamplingFactors(array($factor));
    }
}
