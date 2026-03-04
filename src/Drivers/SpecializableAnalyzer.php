<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers;

use Intervention\Image\Exceptions\LogicException;
use Intervention\Image\Interfaces\AnalyzerInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\SpecializedInterface;

abstract class SpecializableAnalyzer extends Specializable implements AnalyzerInterface
{
    /**
     * {@inheritdoc}
     *
     * @see AnalyzerInterface::analyze()
     *
     * @throws LogicException
     */
    public function analyze(ImageInterface $image): mixed
    {
        if ($this instanceof SpecializedInterface) {
            throw new LogicException(
                "Specialized class '" . static::class . "' must override analyze()"
            );
        }

        return $image->analyze($this);
    }
}
