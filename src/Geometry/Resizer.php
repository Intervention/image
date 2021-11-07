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
    public function __construct(SizeInterface $original)
    {
        $this->original = $original;
        $this->target = new Size(0, 0);
    }

    protected function copyOriginal(): SizeInterface
    {
        return new Size($this->original->getWidth(), $this->original->getHeight());
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
        $this->target = new Size($size->getWidth(), $size->getHeight());

        return $this;
    }

    protected function getProportionalWidth(): int
    {
        if (! $this->hasTargetHeight()) {
            return $this->original->getWidth();
        }

        return (int) round($this->target->getHeight() * $this->original->getAspectRatio());
    }

    protected function getProportionalHeight(): int
    {
        if (! $this->hasTargetWidth()) {
            return $this->original->getHeight();
        }

        return (int) round($this->target->getWidth() / $this->original->getAspectRatio());
    }

    public function resize(): SizeInterface
    {
        $resized = $this->copyOriginal();

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
        $resized = $this->copyOriginal();

        if ($this->hasTargetWidth()) {
            $resized->setWidth(
                min($this->target->getWidth(), $this->original->getWidth())
            );
        }

        if ($this->hasTargetHeight()) {
            $resized->setHeight(
                min($this->target->getHeight(), $this->original->getHeight())
            );
        }

        return $resized;
    }

    public function scale(): SizeInterface
    {
        $resized = $this->copyOriginal();

        if ($this->hasTargetWidth() && $this->hasTargetHeight()) {
            $resized->setWidth(min(
                $this->getProportionalWidth(),
                $this->target->getWidth()
            ));
            $resized->setHeight(min(
                $this->getProportionalHeight(),
                $this->target->getHeight()
            ));
        } elseif ($this->hasTargetWidth()) {
            $resized->setWidth($this->target->getWidth());
            $resized->setHeight($this->getProportionalHeight());
        } elseif ($this->hasTargetHeight()) {
            $resized->setWidth($this->getProportionalWidth());
            $resized->setHeight($this->target->getHeight());
        }

        return $resized;
    }

    public function scaleDown(): SizeInterface
    {
        $resized = $this->copyOriginal();

        if ($this->hasTargetWidth() && $this->hasTargetHeight()) {
            $resized->setWidth(min(
                $this->getProportionalWidth(),
                $this->target->getWidth(),
                $this->original->getWidth()
            ));
            $resized->setHeight(min(
                $this->getProportionalHeight(),
                $this->target->getHeight(),
                $this->original->getHeight()
            ));
        } elseif ($this->hasTargetWidth()) {
            $resized->setWidth(min(
                $this->target->getWidth(),
                $this->original->getWidth()
            ));
            $resized->setHeight(min(
                $this->getProportionalHeight(),
                $this->original->getHeight()
            ));
        } elseif ($this->hasTargetHeight()) {
            $resized->setWidth(min(
                $this->getProportionalWidth(),
                $this->original->getWidth()
            ));
            $resized->setHeight(min(
                $this->target->getHeight(),
                $this->original->getHeight()
            ));
        }

        return $resized;
    }
}
