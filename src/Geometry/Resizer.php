<?php

namespace Intervention\Image\Geometry;

use Intervention\Image\Interfaces\SizeInterface;

/*

modes: fill, contain, cover
resize($width, $height, $mode, $only_reduce)
resize(function($size) {
    $size->width(300);
    $size->contain();
    $size->reduce();
});

- resize
- resizeDown
- scale
- scaleDown
- contain
- containDown
- cover
- coverDown

- resize
- resizeDown
- scale
- scaleDown
- fit(contain|cover)
- fitDown(contain|cover)

- resize
- resizeDown
- scale
- scaleDown
- fit
- fitDown
- pad
- padDown

 */

class Resizer
{
    /**
     * Size to be resized
     *
     * @var SizeInterface
     */
    protected $original;

    /**
     * Target size
     *
     * @var SizeInterface
     */
    protected $target;

    /**
     * Create new instance
     *
     * @param SizeInterface $size
     */
    public function __construct()
    {
        $this->target = new Size(0, 0);
    }

    public static function make(callable $callback = null): self
    {
        $resizer = new self();

        if (is_callable($callback)) {
            $callback($resizer);
        }

        return $resizer;
    }

    protected function hasTargetWidth(): bool
    {
        return $this->target->getWidth() > 0;
    }

    protected function hasTargetHeight(): bool
    {
        return $this->target->getHeight() > 0;
    }

    public function width(int $width): self
    {
        $this->target->setWidth($width);

        return $this;
    }

    public function height(int $height): self
    {
        $this->target->setHeight($height);

        return $this;
    }

    public function toWidth(int $width): self
    {
        return $this->width($width);
    }

    public function toHeight(int $height): self
    {
        return $this->height($height);
    }

    public function setTargetSizeByArray(array $arguments): self
    {
        if (isset($arguments[0]) && is_callable($arguments[0])) {
            $arguments[0]($this);

            return $this;
        }

        if (isset($arguments[0]) && is_numeric($arguments[0])) {
            $this->width($arguments[0]);
        }

        if (isset($arguments[1]) && is_numeric($arguments[1])) {
            $this->height($arguments[1]);
        }

        return $this;
    }

    public function setTargetSize(SizeInterface $size): self
    {
        $this->target = new Size($size->getWidth(), $size->getHeight());

        return $this;
    }

    public function toSize(SizeInterface $size): self
    {
        return $this->setTargetSize($size);
    }

    protected function getProportionalWidth(SizeInterface $size): int
    {
        if (! $this->hasTargetHeight()) {
            return $size->getWidth();
        }

        return (int) round($this->target->getHeight() * $size->getAspectRatio());
    }

    protected function getProportionalHeight(SizeInterface $size): int
    {
        if (! $this->hasTargetWidth()) {
            return $size->getHeight();
        }

        return (int) round($this->target->getWidth() / $size->getAspectRatio());
    }

    public function resize(SizeInterface $size): SizeInterface
    {
        $resized = clone $size;

        if ($this->hasTargetWidth()) {
            $resized->setWidth($this->target->getWidth());
        }

        if ($this->hasTargetHeight()) {
            $resized->setHeight($this->target->getHeight());
        }

        return $resized;
    }

    public function resizeDown(SizeInterface $size): SizeInterface
    {
        $resized = clone $size;

        if ($this->hasTargetWidth()) {
            $resized->setWidth(
                min($this->target->getWidth(), $size->getWidth())
            );
        }

        if ($this->hasTargetHeight()) {
            $resized->setHeight(
                min($this->target->getHeight(), $size->getHeight())
            );
        }

        return $resized;
    }

    public function scale(SizeInterface $size): SizeInterface
    {
        $resized = clone $size;

        if ($this->hasTargetWidth() && $this->hasTargetHeight()) {
            $resized->setWidth(min(
                $this->getProportionalWidth($size),
                $this->target->getWidth()
            ));
            $resized->setHeight(min(
                $this->getProportionalHeight($size),
                $this->target->getHeight()
            ));
        } elseif ($this->hasTargetWidth()) {
            $resized->setWidth($this->target->getWidth());
            $resized->setHeight($this->getProportionalHeight($size));
        } elseif ($this->hasTargetHeight()) {
            $resized->setWidth($this->getProportionalWidth($size));
            $resized->setHeight($this->target->getHeight());
        }

        return $resized;
    }

    public function scaleDown(SizeInterface $size): SizeInterface
    {
        $resized = clone $size;

        if ($this->hasTargetWidth() && $this->hasTargetHeight()) {
            $resized->setWidth(min(
                $this->getProportionalWidth($size),
                $this->target->getWidth(),
                $size->getWidth()
            ));
            $resized->setHeight(min(
                $this->getProportionalHeight($size),
                $this->target->getHeight(),
                $size->getHeight()
            ));
        } elseif ($this->hasTargetWidth()) {
            $resized->setWidth(min(
                $this->target->getWidth(),
                $size->getWidth()
            ));
            $resized->setHeight(min(
                $this->getProportionalHeight($size),
                $size->getHeight()
            ));
        } elseif ($this->hasTargetHeight()) {
            $resized->setWidth(min(
                $this->getProportionalWidth($size),
                $size->getWidth()
            ));
            $resized->setHeight(min(
                $this->target->getHeight(),
                $size->getHeight()
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
        $resized = clone $size;

        // auto height
        $resized->setWidth($this->target->getWidth());
        $resized->setHeight($this->getProportionalHeight($size));

        if ($resized->fitsInto($this->target)) {
            // auto width
            $resized->setWidth($this->getProportionalWidth($size));
            $resized->setHeight($this->target->getHeight());
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
        $resized = clone $size;

        // auto height
        $resized->setWidth($this->target->getWidth());
        $resized->setHeight($this->getProportionalHeight($size));

        if (!$resized->fitsInto($this->target)) {
            // auto width
            $resized->setWidth($this->getProportionalWidth($size));
            $resized->setHeight($this->target->getHeight());
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
            $size->alignPivot($position),
            $position
        );
    }
}
