<?php

namespace Intervention\Image\Commands;

class ChecksumCommand extends AbstractCommand
{
    /**
     * Calculates checksum of given image
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        $this->setOutput(md5($image->encode()));

        return true;
    }
}
