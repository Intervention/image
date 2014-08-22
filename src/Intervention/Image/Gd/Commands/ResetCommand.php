<?php

namespace Intervention\Image\Gd\Commands;

class ResetCommand extends \Intervention\Image\Commands\AbstractCommand
{
    /**
     * Resets given image to its backup state
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        if (is_resource($backup = $image->getBackup())) {

            // destroy old resource
            imagedestroy($image->getCore());
            // reset to new resource
            $image->setCore($backup);

            return true;
        }

        throw new \Intervention\Image\Exception\RuntimeException(
            "Backup not available. Call backup() before reset()."
        );
    }
}
