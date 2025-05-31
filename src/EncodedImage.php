<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Interfaces\EncodedImageInterface;

class EncodedImage extends File implements EncodedImageInterface
{
    /**
     * Create new instance
     *
     * @param string|resource $data
     */
    public function __construct(
        mixed $data,
        protected string $mediaType = 'application/octet-stream'
    ) {
        parent::__construct($data);
    }

    /**
     * {@inheritdoc}
     *
     * @see EncodedImageInterface::mediaType()
     */
    public function mediaType(): string
    {
        return $this->mediaType;
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

    /**
     * Show debug info for the current image
     *
     * @return array<string, mixed>
     */
    public function __debugInfo(): array
    {
        return [
            'mediaType' => $this->mediaType(),
            'size' => $this->size(),
        ];
    }
}
