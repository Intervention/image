<?php

namespace Intervention\Image;

class Origin
{
    /**
     * Create new origin instance
     *
     * @param string $mediaType
     * @param null|string $filePath
     * @return void
     */
    public function __construct(
        protected string $mediaType = 'application/octet-stream',
        protected ?string $filePath = null
    ) {
    }

    /**
     * Return media type of origin
     *
     * @return string
     */
    public function mediaType(): string
    {
        return $this->mediaType;
    }

    /**
     * Alias of self::mediaType()
     */
    public function mimetype(): string
    {
        return $this->mediaType();
    }

    /**
     * Return file path of origin
     *
     * @return null|string
     */
    public function filePath(): ?string
    {
        return $this->filePath;
    }

    /**
     * Set file path for origin
     *
     * @param string $path
     * @return Origin
     */
    public function setFilePath(string $path): self
    {
        $this->filePath = $path;

        return $this;
    }

    /**
     * Return file extension if origin was created from file path
     *
     * @return null|string
     */
    public function fileExtension(): ?string
    {
        return empty($this->filePath) ? null : pathinfo($this->filePath, PATHINFO_EXTENSION);
    }
}
