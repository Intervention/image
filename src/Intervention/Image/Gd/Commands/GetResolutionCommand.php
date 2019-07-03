<?php

namespace Intervention\Image\Gd\Commands;

use Intervention\Image\Commands\AbstractCommand;
use Intervention\Image\Exception\NotSupportedException;
use Intervention\Image\Resolution;

class GetResolutionCommand extends AbstractCommand
{
    /**
     * Reads resolution of given image instance.
     *
     * @param \Intervention\Image\Image $image
     *
     * @return boolean
     * @throws \Intervention\Image\Exception\NotSupportedException
     */
    public function execute($image)
    {
        if (!$this->isSupported()) {
            throw new NotSupportedException(
                "Reading image resolution is not supported by this PHP installation."
            );
        }

        $resolution = imageresolution($image->getCore());
        $resolution = $resolution
            ? new Resolution($resolution[0], $resolution[1], Resolution::UNITS_PPI)
            : false;

        $this->setOutput($resolution);

        return true;
    }

    /**
     * @return bool
     */
    protected function isSupported()
    {
        return function_exists('imageresolution');
    }
}
