<?php

namespace Intervention\Image\Commands;

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
        $data = $image->encode($format, $quality)->getEncoded();

        $this->setOutput($this->getStream($data));

        return true;
    }

    /**
     * Create stream from given data
     *
     * @param  string $data
     * @return \Psr\Http\Message\StreamInterface
     */
    protected function getStream($data)
    {
        if (class_exists(\GuzzleHttp\Psr7\Utils::class)) {
            return \GuzzleHttp\Psr7\Utils::streamFor($data); // guzzlehttp/psr7 >= 2.0
        }

        return \GuzzleHttp\Psr7\stream_for($data); // guzzlehttp/psr7 < 2.0
    }
}
