<?php

namespace Intervention\Image\Imagick\Commands;

class FlipCommand extends \Intervention\Image\Commands\AbstractCommand
{
    /**
     * Mirrors an image
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        $mode = $this->argument(0)->value('h');

        $methodName = $this->modeIsVertical($mode) ? 'flipImage' : 'flopImage';

        foreach ($image as $frame) {
            call_user_func(array($frame->getCore(), $methodName));
        }

        return true;
    }

    /**
     * Check if mode is vertical
     *
     * @param  mixed $mode
     * @return bool
     */
    private function modeIsVertical($mode)
    {
        return in_array(strtolower($mode), array(2, 'v', 'vert', 'vertical'));
    }
}
