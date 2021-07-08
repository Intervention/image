<?php

namespace Intervention\Image\Gd\Commands;

use Intervention\Image\Exception\NotSupportedException;
use Intervention\Image\Imagick\Commands\SetResolutionArguments;
use Intervention\Image\Resolution;

class SetResolutionCommand extends GetResolutionCommand
{
    use SetResolutionArguments;

    /**
     * Updates resolution of given image instance.
     *
     * @param \Intervention\Image\Image $image
     *
     * @return boolean
     * @throws \Intervention\Image\Exception\NotSupportedException
     * @throws \Intervention\Image\Exception\InvalidArgumentException
     */
    public function execute($image)
    {
        // Supported?
        if (!$this->isSupported()) {
            throw new NotSupportedException(
                "Updating image resolution is not supported by this PHP installation."
            );
        }

        // GD Supports only PPI
        $resolution = $this->getInputResolution($image);

        return imageresolution(
            $image->getCore(),
            $resolution->getX(Resolution::UNITS_PPI),
            $resolution->getY(Resolution::UNITS_PPI));
    }
}
