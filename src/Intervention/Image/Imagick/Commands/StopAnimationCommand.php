<?php

namespace Intervention\Image\Imagick\Commands;

use Intervention\Image\Imagick\Container;

class StopAnimationCommand extends \Intervention\Image\Commands\AbstractCommand
{
    /**
     * Removes all frames of an animation except one
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        $keepIndex = $this->argument(0)->type('int')->value(0);

        foreach ($image as $key => $frame) {
            if ($keepIndex == $key) {
                break;
            }
        }

        $frame = $image->getDriver()->init(
            $image->getCore()->getImageBlob()
        );

        // remove old core
        $image->getCore()->clear();

        // set new core
        $image->setContainer($frame->getContainer());

        return true;
    }
}
