<?php

declare(strict_types=1);

namespace Intervention\Image\Geometry\Tools;

use Intervention\Image\Alignment;
use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Exceptions\StateException;
use Intervention\Image\Interfaces\SizeInterface;
use Intervention\Image\Size;

class Resizer
{
    /**
     * @throws InvalidArgumentException
     */
    public function __construct(
        protected ?int $width = null,
        protected ?int $height = null,
    ) {
        if (is_int($width) && $width < 1) {
            throw new InvalidArgumentException(
                'Width must be greater than or equal to 1'
            );
        }

        if (is_int($height) && $height < 1) {
            throw new InvalidArgumentException(
                'Height must be greater than or equal to 1'
            );
        }
    }

    /**
     * Static factory method to create resizer with given target size.
     *
     * @throws InvalidArgumentException
     */
    public static function to(mixed ...$arguments): self
    {
        return new self(...$arguments);
    }

    /**
     * Determine if resize has target width.
     */
    protected function hasTargetWidth(): bool
    {
        return is_int($this->width);
    }

    /**
     * Return target width of resizer if available.
     */
    protected function targetWidth(): ?int
    {
        return $this->hasTargetWidth() ? $this->width : null;
    }

    /**
     * Determine if resize has target height.
     */
    protected function hasTargetHeight(): bool
    {
        return is_int($this->height);
    }

    /**
     * Return target width of resizer if available.
     */
    protected function targetHeight(): ?int
    {
        return $this->hasTargetHeight() ? $this->height : null;
    }

    /**
     * Return target size object.
     *
     * @throws StateException
     */
    protected function targetSize(): SizeInterface
    {
        if (!$this->hasTargetWidth() || !$this->hasTargetHeight()) {
            throw new StateException('Target size needs width and height');
        }

        try {
            return new Size($this->width, $this->height);
        } catch (InvalidArgumentException $e) {
            throw new StateException('Invalid target size', previous: $e);
        }
    }

    /**
     * Set target width of resizer.
     */
    public function toWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Set target height of resizer.
     */
    public function toHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Set target size to given size object.
     */
    public function toSize(SizeInterface $size): self
    {
        $this->width = $size->width();
        $this->height = $size->height();

        return $this;
    }

    /**
     * Get proportinal width.
     */
    protected function proportionalWidth(SizeInterface $size): int
    {
        if (!$this->hasTargetHeight()) {
            return $size->width();
        }

        return max([1, (int) round($this->height * $size->aspectRatio())]);
    }

    /**
     * Get proportinal height.
     */
    protected function proportionalHeight(SizeInterface $size): int
    {
        if (!$this->hasTargetWidth()) {
            return $size->height();
        }

        return max([1, (int) round($this->width / $size->aspectRatio())]);
    }

    /**
     * Resize given size to target size of the resizer.
     *
     * @throws InvalidArgumentException
     */
    public function resize(SizeInterface $size): SizeInterface
    {
        $resized = new Size($size->width(), $size->height());

        if ($width = $this->targetWidth()) {
            $resized->setWidth($width);
        }

        if ($height = $this->targetHeight()) {
            $resized->setHeight($height);
        }

        return $resized;
    }

    /**
     * Resize given size to target size of the resizer but do not exceed original size.
     *
     * @throws InvalidArgumentException
     */
    public function resizeDown(SizeInterface $size): SizeInterface
    {
        $resized = new Size($size->width(), $size->height());

        if ($width = $this->targetWidth()) {
            $resized->setWidth(
                min($width, $size->width())
            );
        }

        if ($height = $this->targetHeight()) {
            $resized->setHeight(
                min($height, $size->height())
            );
        }

        return $resized;
    }

    /**
     * Resize given size to target size proportinally.
     *
     * @throws InvalidArgumentException
     */
    public function scale(SizeInterface $size): SizeInterface
    {
        $resized = new Size($size->width(), $size->height());

        if ($this->hasTargetWidth() && $this->hasTargetHeight()) {
            $resized->setWidth(min(
                $this->proportionalWidth($size),
                $this->targetWidth()
            ));
            $resized->setHeight(min(
                $this->proportionalHeight($size),
                $this->targetHeight()
            ));
        } elseif ($this->hasTargetWidth()) {
            $resized->setWidth($this->targetWidth());
            $resized->setHeight($this->proportionalHeight($size));
        } elseif ($this->hasTargetHeight()) {
            $resized->setWidth($this->proportionalWidth($size));
            $resized->setHeight($this->targetHeight());
        }

        return $resized;
    }

    /**
     * Resize given size to target size proportinally but do not exceed original size.
     *
     * @throws InvalidArgumentException
     */
    public function scaleDown(SizeInterface $size): SizeInterface
    {
        $resized = new Size($size->width(), $size->height());

        if ($this->hasTargetWidth() && $this->hasTargetHeight()) {
            $resized->setWidth(min(
                $this->proportionalWidth($size),
                $this->targetWidth(),
                $size->width()
            ));
            $resized->setHeight(min(
                $this->proportionalHeight($size),
                $this->targetHeight(),
                $size->height()
            ));
        } elseif ($this->hasTargetWidth()) {
            $resized->setWidth(min(
                $this->targetWidth(),
                $size->width()
            ));
            $resized->setHeight(min(
                $this->proportionalHeight($size),
                $size->height()
            ));
        } elseif ($this->hasTargetHeight()) {
            $resized->setWidth(min(
                $this->proportionalWidth($size),
                $size->width()
            ));
            $resized->setHeight(min(
                $this->targetHeight(),
                $size->height()
            ));
        }

        return $resized;
    }

    /**
     * Scale given size to cover target size.
     *
     * @param SizeInterface $size Size to be resized
     * @throws InvalidArgumentException
     * @throws StateException
     */
    public function cover(SizeInterface $size): SizeInterface
    {
        $resized = new Size($size->width(), $size->height());

        // auto height
        $resized->setWidth($this->targetWidth());
        $resized->setHeight($this->proportionalHeight($size));

        if ($resized->fitsInto($this->targetSize())) {
            // auto width
            $resized->setWidth($this->proportionalWidth($size));
            $resized->setHeight($this->targetHeight());
        }

        return $resized;
    }

    /**
     * Scale the given size up or down so that the result can fit into the target size.
     *
     * @param SizeInterface $size Size to be resized
     * @throws InvalidArgumentException
     * @throws StateException
     */
    public function contain(SizeInterface $size): SizeInterface
    {
        $resized = new Size($size->width(), $size->height());

        // auto height
        $resized->setWidth($this->targetWidth());
        $resized->setHeight($this->proportionalHeight($size));

        if (!$resized->fitsInto($this->targetSize())) {
            // auto width
            $resized->setWidth($this->proportionalWidth($size));
            $resized->setHeight($this->targetHeight());
        }

        return $resized;
    }

    /**
     * Scale the given size down so that the result can fit into the target size.
     *
     * @param SizeInterface $size Size to be resized
     * @throws InvalidArgumentException
     * @throws StateException
     */
    public function containDown(SizeInterface $size): SizeInterface
    {
        $resized = new Size($size->width(), $size->height());

        // auto height
        $resized->setWidth(
            min($size->width(), $this->targetWidth())
        );

        $resized->setHeight(
            min($size->height(), $this->proportionalHeight($size))
        );

        if (!$resized->fitsInto($this->targetSize())) {
            // auto width
            $resized->setWidth(
                min($size->width(), $this->proportionalWidth($size))
            );
            $resized->setHeight(
                min($size->height(), $this->targetHeight())
            );
        }

        return $resized;
    }

    /**
     * Crop target size out of given size at given alignment position (i.e. move the pivot point).
     *
     * @throws InvalidArgumentException
     */
    public function crop(SizeInterface $size, string|Alignment $alignment = Alignment::TOP_LEFT): SizeInterface
    {
        return $this->resize($size)->alignPivotTo(
            $size->movePivot($alignment),
            $alignment
        );
    }
}
