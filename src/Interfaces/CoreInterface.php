<?php

namespace Intervention\Image\Interfaces;

use Traversable;

interface CoreInterface extends Traversable
{
    public function native(): mixed;
    public function setNative(mixed $native): CoreInterface;
    public function count(): int;
    public function frame(int $position): FrameInterface;
    public function loops(): int;
    public function setLoops(int $loops): CoreInterface;
}
