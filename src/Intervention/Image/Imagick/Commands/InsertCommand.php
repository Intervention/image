<?php

namespace Intervention\Image\Imagick\Commands;

use \Intervention\Image\Imagick\Source;

class InsertCommand extends \Intervention\Image\Commands\AbstractCommand
{
    public function execute($image)
    {
        $source = $this->getArgument(0);
        $position = $this->getArgument(1);
        $x = intval($this->getArgument(2));
        $y = intval($this->getArgument(3));

        // build watermark
        $watermark = $image->getDriver()->init($source);

        // define insertion point
        $image_size = $image->getSize()->align($position, $x, $y);
        $watermark_size = $watermark->getSize()->align($position);
        $target = $image_size->relativePosition($watermark_size);

        // insert image at position
        return $image->getCore()->compositeImage($watermark->getCore(), \Imagick::COMPOSITE_DEFAULT, $target->x, $target->y);
    }
}
