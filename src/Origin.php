<?php

declare(strict_types=1);

namespace Intervention\Image;

class Origin
{
    /**
     * Create new origin instance
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
     * Return media type of origin
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
     * Set media type of current instance
     */
    public function setMediaType(string|MediaType $type): self
    {
        $this->mediaType = match (true) {
            is_string($type) => $type,
            default => $type->value,
        };

        return $this;
    }

    /**
     * Return file path of origin
     */
    public function filePath(): ?string
    {
        return $this->filePath;
    }

    /**
     * Set file path for origin
     */
    public function setFilePath(string $path): self
    {
        $this->filePath = $path;

        return $this;
    }

    /**
     * Return file extension if origin was created from file path
     */
    public function fileExtension(): ?string
    {
        return pathinfo($this->filePath ?: '', PATHINFO_EXTENSION) ?: null;
    }

    /**
     * Show debug info for the current image
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
