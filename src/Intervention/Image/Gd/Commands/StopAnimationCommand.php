<?php

namespace Intervention\Image\Gd\Commands;

use Intervention\Image\Gd\Container;

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

        $container = new Container;
        $container->add($image->getCore($keepIndex));
        $image->setContainer($container);

        return true;
    }
}
