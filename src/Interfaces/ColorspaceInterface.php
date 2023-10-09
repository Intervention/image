<?php

namespace Intervention\Image\Interfaces;

interface ColorspaceInterface
{
    public function transformColor(ColorInterface $color): ColorInterface;
}
