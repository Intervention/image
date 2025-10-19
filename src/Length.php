<?php

declare(strict_types=1);

namespace Intervention\Image;

enum Length: string
{
    case INCH = 'inch';
    case CM = 'cm';
    case PX = 'px';
}
