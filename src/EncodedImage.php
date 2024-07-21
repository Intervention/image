<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Interfaces\EncodedImageInterface;

class EncodedImage extends File implements EncodedImageInterface
{
    /**
     * Create new instance
     *
     * @param mixed $data
     * @param string $mediaType deprecated
     */
    public function __construct(
        mixed $data,
        protected string $mediaType = 'application/octet-stream'
    ) {
        parent::__construct($data);

        if (count(func_get_args()) > 1) {
            trigger_error('Argument #2 ($mediaType) has been deprecated as of version 3.8.0.', E_USER_DEPRECATED);
        }
    }
}
