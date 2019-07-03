<?php

namespace Intervention\Image\Imagick\Commands;

class SetResolutionCommand extends GetResolutionCommand
{
    use SetResolutionArguments;

    /**
     * Updates resolution of given image instance.
     *
     * @param \Intervention\Image\Image $image
     *
     * @return boolean
     */
    public function execute($image)
    {
        /** @var \Imagick $core */
        $core       = $image->getCore();
        $resolution = $this->getInputResolution($image);

        return $core->setImageUnits($this->getImagickUnits($resolution->getUnits()))
               && $core->setImageResolution($resolution->getX(), $resolution->getY());
    }
}
