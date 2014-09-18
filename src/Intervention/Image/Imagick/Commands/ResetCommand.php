<?php

namespace Intervention\Image\Imagick\Commands;

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
        $backupName = $this->argument(0)->value();

        $backup = $image->getBackup($backupName);

        if ($backup instanceof \Imagick) {

            // destroy old core
            $image->getCore()->clear();

            // reset to new resource
            $image->setCore($backup);

            return true;
        }

        throw new \Intervention\Image\Exception\RuntimeException(
            "Backup not available. Call backup({$backupName}) before reset()."
        );
    }
}
