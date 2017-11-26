<?php

namespace Intervention\Image\Imagick\Commands;

use Intervention\Image\Commands\ExifCommand as BaseCommand;

class ExifCommand extends BaseCommand
{
    /**
     * Prefer extension or not
     *
     * @var bool
     */
    private $preferExtension = true;

    /**
     *
     */
    public function dontPreferExtension() {
        $this->preferExtension = false;
    }

    /**
     * Read Exif data from the given image
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        if ($this->preferExtension && function_exists('exif_read_data')) {
            return parent::execute($image);
        }

        $core = $image->getCore();

        if ( ! method_exists($core, 'getImageProperties')) {
            return true;
        }

        $requestedKey = $this->argument(0)->value();
        if ($requestedKey !== null) {
            $this->setOutput($core->getImageProperty('exif:' . $requestedKey));
            return true;
        }

        $exif = [];
        $properties = $core->getImageProperties();
        foreach ($properties as $key => $value) {
            if (substr($key, 0, 5) !== 'exif:') {
                continue;
            }

            $exif[substr($key, 5)] = $value;
        }

        $this->setOutput($exif);
        return true;
    }
}
