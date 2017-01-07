<?php

namespace Intervention\Image\Imagick\Commands;

use Intervention\Image\Commands\ExifCommand as BaseCommand;

class ExifCommand extends BaseCommand
{
    /**
     * Read Exif data from the given image
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        $core = $image->getCore();

        // when getImageProperty is not supported fallback to default exif command
        if ( ! method_exists($core, 'getImageProperties')) {
            return parent::execute($image);
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

            $exif[substr($key, 6)] = $value;
        }

        $this->setOutput($exif);
        return true;
    }
}
