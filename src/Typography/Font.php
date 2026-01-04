<?php

declare(strict_types=1);

namespace Intervention\Image\Typography;

use Intervention\Image\Alignment;
use Intervention\Image\Exceptions\DirectoryNotFoundException;
use Intervention\Image\Exceptions\FileNotFoundException;
use Intervention\Image\Exceptions\FileNotReadableException;
use Intervention\Image\Exceptions\FilesystemException;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\FontInterface;
use Intervention\Image\Traits\CanParseFilePath;

class Font implements FontInterface
{
    use CanParseFilePath;

    public function __construct(
        protected ?string $filepath = null,
        protected float $size = 12,
        protected float $angle = 0,
        protected mixed $color = '000000',
        protected mixed $strokeColor = 'ffffff',
        protected int $strokeWidth = 0,
        protected Alignment $alignment = Alignment::LEFT,
        protected Alignment $verticalAlignment = Alignment::BOTTOM,
        protected float $lineHeight = 1.25,
        protected ?int $wrapWidth = null,
    ) {
        //
    }

    /**
     * {@inheritdoc}
     *
     * @see FontInterface::setSize()
     */
    public function setSize(float $size): FontInterface
    {
        $this->size = $size;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see FontInterface::size()
     */
    public function size(): float
    {
        return $this->size;
    }

    /**
     * {@inheritdoc}
     *
     * @see FontInterface::setAngle()
     */
    public function setAngle(float $angle): FontInterface
    {
        $this->angle = $angle;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see FontInterface::angle()
     */
    public function angle(): float
    {
        return $this->angle;
    }

    /**
     * {@inheritdoc}
     *
     * @see FontInterface::setFilepath()
     *
     * @throws InvalidArgumentException
     * @throws DirectoryNotFoundException
     * @throws FileNotFoundException
     * @throws FileNotReadableException
     */
    public function setFilepath(string $path): FontInterface
    {
        $this->filepath = $this->readableFilePathOrFail($path);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see FontInterface::filepath()
     */
    public function filepath(): ?string
    {
        try {
            return $this->readableFilePathOrFail($this->filepath);
        } catch (FilesystemException | InvalidArgumentException) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see FontInterface::hasFile()
     */
    public function hasFile(): bool
    {
        return $this->filepath() !== null;
    }

    /**
     * {@inheritdoc}
     *
     * @see FontInterface::setColor()
     */
    public function setColor(mixed $color): FontInterface
    {
        $this->color = $color;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see FontInterface::color()
     */
    public function color(): mixed
    {
        return $this->color;
    }

    /**
     * {@inheritdoc}
     *
     * @see FontInterface::setStrokeColor()
     */
    public function setStrokeColor(mixed $color): FontInterface
    {
        $this->strokeColor = $color;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see FontInterface::strokeColor()
     */
    public function strokeColor(): mixed
    {
        return $this->strokeColor;
    }

    /**
     * {@inheritdoc}
     *
     * @see FontInterface::setStrokeWidth()
     *
     * @throws InvalidArgumentException
     */
    public function setStrokeWidth(int $width): FontInterface
    {
        if (!in_array($width, range(0, 10))) {
            throw new InvalidArgumentException(
                'The stroke width must be in the range from 0 to 10'
            );
        }

        $this->strokeWidth = $width;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see FontInterface::strokeWidth()
     */
    public function strokeWidth(): int
    {
        return $this->strokeWidth;
    }

    /**
     * {@inheritdoc}
     *
     * @see FontInterface::hasStrokeEffect()
     */
    public function hasStrokeEffect(): bool
    {
        return $this->strokeWidth > 0;
    }

    /**
     * {@inheritdoc}
     *
     * @see FontInterface::alignment()
     */
    public function alignment(): Alignment
    {
        return $this->alignment;
    }

    /**
     * {@inheritdoc}
     *
     * @see FontInterface::setAlignment()
     */
    public function setAlignment(string|Alignment $alignment): FontInterface
    {
        $this->alignment = is_string($alignment) ? Alignment::from($alignment) : $alignment;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see FontInterface::verticalAlignment()
     */
    public function verticalAlignment(): Alignment
    {
        return $this->verticalAlignment;
    }

    /**
     * {@inheritdoc}
     *
     * @see FontInterface::setVerticalAlignment()
     */
    public function setVerticalAlignment(string|Alignment $alignment): FontInterface
    {
        $this->verticalAlignment = is_string($alignment) ? Alignment::from($alignment) : $alignment;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see FontInterface::setLineHeight()
     */
    public function setLineHeight(float $height): FontInterface
    {
        $this->lineHeight = $height;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see FontInterface::lineHeight()
     */
    public function lineHeight(): float
    {
        return $this->lineHeight;
    }

    /**
     * {@inheritdoc}
     *
     * @see FontInterface::setWrapWidth()
     */
    public function setWrapWidth(?int $width): FontInterface
    {
        $this->wrapWidth = $width;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see FontInterface::wrapWidth()
     */
    public function wrapWidth(): ?int
    {
        return $this->wrapWidth;
    }
}
