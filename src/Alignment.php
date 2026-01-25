<?php

declare(strict_types=1);

namespace Intervention\Image;

use Error;
use Intervention\Image\Exceptions\InvalidArgumentException;

/**
 * todo: add test
 */
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

    public function alignHorizontally(string|Alignment $alignment): self
    {
        // handle "leftish" alignments
        if (in_array($alignment, [self::LEFT, self::BOTTOM_LEFT, self::TOP_LEFT])) {
            return match ($this) {
                self::TOP, self::TOP_RIGHT, self::TOP_LEFT => self::TOP_LEFT,
                self::BOTTOM, self::BOTTOM_RIGHT, self::BOTTOM_LEFT => self::BOTTOM_LEFT,
                self::CENTER, self::LEFT, self::RIGHT => self::LEFT,
            };
        }

        // handle "rightish" alignments
        if (in_array($alignment, [self::RIGHT, self::TOP_RIGHT, self::BOTTOM_RIGHT])) {
            return match ($this) {
                self::TOP, self::TOP_RIGHT, self::TOP_LEFT => self::TOP_RIGHT,
                self::BOTTOM, self::BOTTOM_RIGHT, self::BOTTOM_LEFT => self::BOTTOM_RIGHT,
                self::CENTER, self::LEFT, self::RIGHT => self::RIGHT,
            };
        }

        // handle centering
        if (in_array($alignment, [self::CENTER])) {
            return match ($this) {
                self::TOP, self::TOP_RIGHT, self::TOP_LEFT => self::TOP,
                self::BOTTOM, self::BOTTOM_RIGHT, self::BOTTOM_LEFT => self::BOTTOM,
                self::CENTER, self::LEFT, self::RIGHT => self::CENTER,
            };
        }

        return $this;
    }

    public function alignVertically(string|Alignment $alignment): self
    {
        // handle "bottomish" alignments
        if (in_array($alignment, [self::BOTTOM, self::BOTTOM_RIGHT, self::BOTTOM_LEFT])) {
            return match ($this) {
                self::LEFT, self::TOP_LEFT, self::BOTTOM_LEFT => self::BOTTOM_LEFT,
                self::RIGHT, self::TOP_RIGHT, self::BOTTOM_RIGHT => self::BOTTOM_RIGHT,
                self::CENTER, self::TOP, self::BOTTOM => self::BOTTOM,
            };
        }

        // handle "topish" alignments
        if (in_array($alignment, [self::TOP, self::TOP_RIGHT, self::TOP_LEFT])) {
            return match ($this) {
                self::LEFT, self::TOP_LEFT, self::BOTTOM_LEFT => self::TOP_LEFT,
                self::RIGHT, self::TOP_RIGHT, self::BOTTOM_RIGHT => self::TOP_RIGHT,
                self::CENTER, self::TOP, self::BOTTOM => self::TOP,
            };
        }

        // handle centering
        if (in_array($alignment, [self::CENTER])) {
            return match ($this) {
                self::LEFT, self::TOP_LEFT, self::BOTTOM_LEFT => self::LEFT,
                self::RIGHT, self::TOP_RIGHT, self::BOTTOM_RIGHT => self::RIGHT,
                self::CENTER, self::TOP, self::BOTTOM => self::CENTER,
            };
        }

        return $this;
    }

    /**
     * Return only the horizontal alignment.
     */
    public function horizontal(): self
    {
        return match ($this) {
            self::TOP, self::CENTER, self::BOTTOM => self::CENTER,
            self::RIGHT, self::TOP_RIGHT, self::BOTTOM_RIGHT => self::RIGHT,
            self::LEFT, self::TOP_LEFT, self::BOTTOM_LEFT => self::LEFT,
        };
    }

    /**
     * Return only the vertical alignment.
     */
    public function vertical(): self
    {
        return match ($this) {
            self::CENTER, self::RIGHT, self::LEFT => self::CENTER,
            self::TOP, self::TOP_RIGHT, self::TOP_LEFT => self::TOP,
            self::BOTTOM, self::BOTTOM_RIGHT, self::BOTTOM_LEFT => self::BOTTOM,
        };
    }
}
