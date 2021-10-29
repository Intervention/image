<?php

namespace Intervention\Image\Geometry;

use Intervention\Image\Interfaces\SizeInterface;

class Resizer
{
    /**
     * Size to be resized
     *
     * @var SizeInterface
     */
    protected $size;

    /**
     * Target size
     *
     * @var SizeInterface
     */
    protected $target;

    public function __construct(SizeInterface $size)
    {
        $this->size = $size;
        $this->target = new Size(0, 0);
    }

    public static function fromSize(SizeInterface $size): self
    {
        return new self($size);
    }

    public function getSize(): SizeInterface
    {
        return $this->size;
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
        $this->target = $size;

        return $this;
    }

    public function resize(): SizeInterface
    {
        $resized = clone $this->size;

        if ($this->hasTargetWidth()) {
            $resized->setWidth($this->target->getWidth());
        }

        if ($this->hasTargetHeight()) {
            $resized->setHeight($this->target->getHeight());
        }

        return $resized;
    }

    public function resizeDown(): SizeInterface
    {
        if ($this->hasTargetWidth()) {
            $this->target->setWidth(
                min($this->target->getWidth(), $this->size->getWidth())
            );
        }

        if ($this->hasTargetHeight()) {
            $this->target->setHeight(
                min($this->target->getHeight(), $this->size->getHeight())
            );
        }

        return $this->resize();
    }

    public function scale(): SizeInterface
    {
        if ($this->hasTargetWidth() && $this->hasTargetHeight()) {
            $this->target->setWidth(min(
                $this->getProportionalWidth(),
                $this->target->getWidth()
            ));
            $this->target->setHeight(min(
                $this->getProportionalHeight(),
                $this->target->getHeight()
            ));
        } elseif ($this->hasTargetWidth()) {
            $this->target->setHeight($this->getProportionalHeight());
        } elseif ($this->hasTargetHeight()) {
            $this->target->setWidth($this->getProportionalWidth());
        }

        return $this->resize();
    }

    public function scaleDown(): SizeInterface
    {
        if ($this->hasTargetWidth() && $this->hasTargetHeight()) {
            $this->target->setWidth(min(
                $this->getProportionalWidth(),
                $this->target->getWidth(),
                $this->size->getWidth()
            ));
            $this->target->setHeight(min(
                $this->getProportionalHeight(),
                $this->target->getHeight(),
                $this->size->getHeight()
            ));
        } elseif ($this->hasTargetWidth()) {
            $this->target->setWidth(min(
                $this->target->getWidth(),
                $this->size->getWidth()
            ));
            $this->target->setHeight(min(
                $this->getProportionalHeight(),
                $this->size->getHeight()
            ));
        } elseif ($this->hasTargetHeight()) {
            $this->target->setWidth(min(
                $this->getProportionalWidth(),
                $this->size->getWidth()
            ));
            $this->target->setHeight(min(
                $this->target->getHeight(),
                $this->size->getHeight()
            ));
        }

        return $this->resize();
    }

    protected function getProportionalWidth(): int
    {
        if (! $this->hasTargetHeight()) {
            return $this->size->getWidth();
        }

        return (int) round($this->target->getHeight() * $this->size->getAspectRatio());
    }

    protected function getProportionalHeight(): int
    {
        if (! $this->hasTargetWidth()) {
            return $this->size->getHeight();
        }

        return (int) round($this->target->getWidth() / $this->size->getAspectRatio());
    }
}
