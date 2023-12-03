<?php

namespace Intervention\Image\Geometry\Tools;

use Intervention\Image\Exceptions\GeometryException;
use Intervention\Image\Geometry\Rectangle;
use Intervention\Image\Interfaces\SizeInterface;

class RectangleResizer
{
    public function __construct(
        protected ?int $width = null,
        protected ?int $height = null,
    ) {
        //
    }

    public static function to(...$arguments): self
    {
        return new self(...$arguments);
    }

    protected function hasTargetWidth(): bool
    {
        return is_integer($this->width);
    }

    protected function getTargetWidth(): ?int
    {
        return $this->hasTargetWidth() ? $this->width : null;
    }

    protected function hasTargetHeight(): bool
    {
        return is_integer($this->height);
    }

    protected function getTargetHeight(): ?int
    {
        return $this->hasTargetHeight() ? $this->height : null;
    }

    protected function getTargetSize(): SizeInterface
    {
        if (!$this->hasTargetWidth() || !$this->hasTargetHeight()) {
            throw new GeometryException('Target size needs width and height.');
        }

        return new Rectangle($this->width, $this->height);
    }

    public function toWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function toHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function toSize(SizeInterface $size): self
    {
        $this->width = $size->width();
        $this->height = $size->height();

        return $this;
    }

    protected function getProportionalWidth(SizeInterface $size): int
    {
        if (!$this->hasTargetHeight()) {
            return $size->width();
        }

        return (int) round($this->height * $size->aspectRatio());
    }

    protected function getProportionalHeight(SizeInterface $size): int
    {
        if (!$this->hasTargetWidth()) {
            return $size->height();
        }

        return (int) round($this->width / $size->aspectRatio());
    }

    public function resize(SizeInterface $size): SizeInterface
    {
        $resized = new Rectangle($size->width(), $size->height());

        if ($width = $this->getTargetWidth()) {
            $resized->setWidth($width);
        }

        if ($height = $this->getTargetHeight()) {
            $resized->setHeight($height);
        }

        return $resized;
    }

    public function resizeDown(SizeInterface $size): SizeInterface
    {
        $resized = new Rectangle($size->width(), $size->height());

        if ($width = $this->getTargetWidth()) {
            $resized->setWidth(
                min($width, $size->width())
            );
        }

        if ($height = $this->getTargetHeight()) {
            $resized->setHeight(
                min($height, $size->height())
            );
        }

        return $resized;
    }

    public function scale(SizeInterface $size): SizeInterface
    {
        $resized = new Rectangle($size->width(), $size->height());

        if ($this->hasTargetWidth() && $this->hasTargetHeight()) {
            $resized->setWidth(min(
                $this->getProportionalWidth($size),
                $this->getTargetWidth()
            ));
            $resized->setHeight(min(
                $this->getProportionalHeight($size),
                $this->getTargetHeight()
            ));
        } elseif ($this->hasTargetWidth()) {
            $resized->setWidth($this->getTargetWidth());
            $resized->setHeight($this->getProportionalHeight($size));
        } elseif ($this->hasTargetHeight()) {
            $resized->setWidth($this->getProportionalWidth($size));
            $resized->setHeight($this->getTargetHeight());
        }

        return $resized;
    }

    public function scaleDown(SizeInterface $size): SizeInterface
    {
        $resized = new Rectangle($size->width(), $size->height());

        if ($this->hasTargetWidth() && $this->hasTargetHeight()) {
            $resized->setWidth(min(
                $this->getProportionalWidth($size),
                $this->getTargetWidth(),
                $size->width()
            ));
            $resized->setHeight(min(
                $this->getProportionalHeight($size),
                $this->getTargetHeight(),
                $size->height()
            ));
        } elseif ($this->hasTargetWidth()) {
            $resized->setWidth(min(
                $this->getTargetWidth(),
                $size->width()
            ));
            $resized->setHeight(min(
                $this->getProportionalHeight($size),
                $size->height()
            ));
        } elseif ($this->hasTargetHeight()) {
            $resized->setWidth(min(
                $this->getProportionalWidth($size),
                $size->width()
            ));
            $resized->setHeight(min(
                $this->getTargetHeight(),
                $size->height()
            ));
        }

        return $resized;
    }

    /**
     * Scale given size to cover target size
     *
     * @param  SizeInterface $size Size to be resized
     * @return SizeInterface
     */
    public function cover(SizeInterface $size): SizeInterface
    {
        $resized = new Rectangle($size->width(), $size->height());

        // auto height
        $resized->setWidth($this->getTargetWidth());
        $resized->setHeight($this->getProportionalHeight($size));

        if ($resized->fitsInto($this->getTargetSize())) {
            // auto width
            $resized->setWidth($this->getProportionalWidth($size));
            $resized->setHeight($this->getTargetHeight());
        }

        return $resized;
    }

    /**
     * Scale given size to contain target size
     *
     * @param  SizeInterface $size Size to be resized
     * @return SizeInterface
     */
    public function contain(SizeInterface $size): SizeInterface
    {
        $resized = new Rectangle($size->width(), $size->height());

        // auto height
        $resized->setWidth($this->getTargetWidth());
        $resized->setHeight($this->getProportionalHeight($size));

        if (!$resized->fitsInto($this->getTargetSize())) {
            // auto width
            $resized->setWidth($this->getProportionalWidth($size));
            $resized->setHeight($this->getTargetHeight());
        }

        return $resized;
    }

    /**
     * Scale given size to contain target size but prevent upsizing
     *
     * @param  SizeInterface $size Size to be resized
     * @return SizeInterface
     */
    public function containDown(SizeInterface $size): SizeInterface
    {
        $resized = new Rectangle($size->width(), $size->height());

        // auto height
        $resized->setWidth(
            min($size->width(), $this->getTargetWidth())
        );

        $resized->setHeight(
            min($size->height(), $this->getProportionalHeight($size))
        );

        if (!$resized->fitsInto($this->getTargetSize())) {
            // auto width
            $resized->setWidth(
                min($size->width(), $this->getProportionalWidth($size))
            );
            $resized->setHeight(
                min($size->height(), $this->getTargetHeight())
            );
        }

        return $resized;
    }

    /**
     * Crop target size out of given size at given position (i.e. move the pivot point)
     *
     * @param  SizeInterface $size
     * @param  string        $position
     * @return SizeInterface
     */
    public function crop(SizeInterface $size, string $position = 'top-left'): SizeInterface
    {
        return $this->resize($size)->alignPivotTo(
            $size->movePivot($position),
            $position
        );
    }
}
