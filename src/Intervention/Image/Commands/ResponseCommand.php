<?php

namespace Intervention\Image\Commands;

use \Intervention\Image\Response;

class ResponseCommand extends AbstractCommand
{
    public function execute($image)
    {
        $format = $this->getArgument(0);
        $quality = $this->getArgument(1);

        $response = new Response($image, $format, $quality);

        $this->setOutput($response->make());

        return true;
    }
}
