<?php

declare(strict_types=1);

namespace Intervention\Image;

use Error;
use Intervention\Image\Exceptions\InvalidArgumentException;

enum Alignment: string
{
    case TOP = 'top';
    case TOP_RIGHT = 'top-right';
    case RIGHT = 'right';
    case BOTTOM_RIGHT = 'bottom-right';
    case BOTTOM = 'bottom';
    case BOTTOM_LEFT = 'bottom-left';
    case LEFT = 'left';
    case TOP_LEFT = 'top-left';
    case CENTER = 'center';

    /**
     * Create position from given identifier.
     *
     * @throws InvalidArgumentException
     */
    public static function create(string|self $identifier): self
    {
        if ($identifier instanceof self) {
            return $identifier;
        }

        try {
            $position = self::from(strtolower($identifier));
        } catch (Error) {
            $position = match (strtolower($identifier)) {
                'top-center',
                'center-top',
                'top-middle',
                'middle-top' => self::TOP,

                'right-top' => self::TOP_RIGHT,

                'right-center',
                'center-right',
                'right-middle',
                'middle-right' => self::RIGHT,

                'right-bottom' => self::BOTTOM_RIGHT,

                'bottom-center',
                'center-bottom',
                'bottom-middle',
                'middle-bottom' => self::BOTTOM,

                'left-bottom' => self::BOTTOM_LEFT,

                'left-center',
                'center-left',
                'left-middle',
                'middle-left' => self::LEFT,

                'left-top' => self::TOP_LEFT,

                'middle',
                'center-center',
                'center-middle',
                'middle-center' => self::CENTER,

                default => throw new InvalidArgumentException(
                    'Unable to create ' . self::class . ' from "' . $identifier . '"',
                ),
            };
        }

        return $position;
    }

    /**
     * Try to create position from given identifier or return null on failure.
     */
    public static function tryCreate(string|self $identifier): ?self
    {
        try {
            return self::create($identifier);
        } catch (InvalidArgumentException) {
            return null;
        }
    }
}
