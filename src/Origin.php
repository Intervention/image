<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Interfaces\OriginInterface;

class Origin implements OriginInterface
{
    /**
     * Create new origin instance.
     *
     * @return void
     */
    public function __construct(
        protected string $mediaType = 'application/octet-stream',
        protected ?string $filePath = null
    ) {
        //
    }

    /**
     * {@inheritdoc}
     *
     * @see OriginInterface::mediaType()
     */
    public function mediaType(): string
    {
        return $this->mediaType;
    }

    /**
     * @see self::mediaType()
     */
    public function mimetype(): string
    {
        return $this->mediaType();
    }

    /**
     * {@inheritdoc}
     *
     * @see OriginInterface::setMediaType()
     */
    public function setMediaType(string|MediaType $type): self
    {
        $this->mediaType = is_string($type) ? $type : $type->value;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see OriginInterface::filePath()
     */
    public function filePath(): ?string
    {
        return $this->filePath;
    }

    /**
     * {@inheritdoc}
     *
     * @see OriginInterface::setFilePath()
     */
    public function setFilePath(string $path): self
    {
        $this->filePath = $path;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see OriginInterface::fileExtension()
     */
    public function fileExtension(): ?string
    {
        return pathinfo($this->filePath ?: '', PATHINFO_EXTENSION) ?: null;
    }

    /**
     * {@inheritdoc}
     *
     * @see OriginInterface::format()
     *
     * @throws NotSupportedException
     */
    public function format(): Format
    {
        try {
            return MediaType::create($this->mediaType())->format();
        } catch (InvalidArgumentException) {
            throw new NotSupportedException('Media type "' . $this->mediaType() . '" is not supported');
        }
    }

    /**
     * Show debug info for the current image.
     *
     * @return array<string, null|string>
     */
    public function __debugInfo(): array
    {
        return [
            'mediaType' => $this->mediaType(),
            'filePath' => $this->filePath(),
        ];
    }
}
