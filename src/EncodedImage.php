<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Interfaces\EncodedImageInterface;

class EncodedImage extends File implements EncodedImageInterface
{
    /**
     * Create new instance
     *
     * @param string $data
     * @param string $mediaType Deprecated parameter, will be removed
     */
    public function __construct(
        string $data,
        protected string $mediaType = 'application/octet-stream' // deprecated
    ) {
        if ($mediaType !== 'application/octet-stream') {
            trigger_error('Parameter $mediaType for class' . self::class . ' is deprecated.', E_USER_DEPRECATED);
        }

        parent::__construct($data);
    }

    /**
     * {@inheritdoc}
     *
     * @see EncodedImageInterface::mediaType()
     */
    public function mediaType(): string
    {
        return mime_content_type($this->pointer);
    }

    /**
     * {@inheritdoc}
     *
     * @see EncodedImageInterface::mimetype()
     */
    public function mimetype(): string
    {
        return $this->mediaType();
    }

    /**
     * {@inheritdoc}
     *
     * @see EncodedImageInterface::toDataUri()
     */
    public function toDataUri(): string
    {
        return sprintf('data:%s;base64,%s', $this->mediaType(), base64_encode((string) $this));
    }
}
