<?php

namespace Intervention\Image\Commands;

class ExifCommand extends AbstractCommand
{
    public function execute($image)
    {
        if ( ! function_exists('exif_read_data')) {
            throw new \Intervention\Image\Exception\NotSupportedException(
                "Reading Exif data is not supported by this PHP installation."
            );
        }

        $key = $this->getArgument(0);
        $data = exif_read_data($image->dirname .'/'. $image->basename, 'EXIF', false);

        if (! is_null($key) && is_array($data)) {
            $data = array_key_exists($key, $data) ? $data[$key] : false;
        }

        $this->setOutput($data);

        return true;
    }
}
