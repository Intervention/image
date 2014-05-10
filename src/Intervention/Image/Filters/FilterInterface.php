<?php

namespace Intervention\Image\Filters;

interface FilterInterface
{
    public function applyFilter(\Intervention\Image\Image $image);
}
