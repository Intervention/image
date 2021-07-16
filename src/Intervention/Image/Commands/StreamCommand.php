<?php

namespace Intervention\Image\Commands;

use GuzzleHttp\Psr7\Utils;

class StreamCommand extends AbstractCommand
{
    /**
     * Builds PSR7 stream based on image data. Method uses Guzzle PSR7
     * implementation as easiest choice.
     *
     * @param  \Intervention\Image\Image $image
     * @return boolean
     */
    public function execute($image)
    {
        $format = $this->argument(0)->value();
        $quality = $this->argument(1)->between(0, 100)->value();

        $encoded_image = $image->encode($format, $quality)->getEncoded();
        
        // For backwards compatibility with guzzlehttp/psr7 ~1.1
        $output = function_exists('\GuzzleHttp\Psr7\stream_for') ? \GuzzleHttp\Psr7\stream_for($encoded_image) : Utils::streamFor($encoded_image);

        $this->setOutput($output);

        return true;
    }
}