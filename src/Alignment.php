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
                'top_center',
                'topcenter',
                'center-top',
                'center_top',
                'centertop',
                'top-middle',
                'top_middle',
                'topmiddle',
                'middle-top',
                'middle_top',
                'middletop' => self::TOP,

                'top_right',
                'topright',
                'right-top',
                'right_top',
                'righttop' => self::TOP_RIGHT,

                'right-center',
                'right_center',
                'rightcenter',
                'center-right',
                'center_right',
                'centerright',
                'right-middle',
                'right_middle',
                'rightmiddle',
                'middle-right',
                'middle_right',
                'middleright' => self::RIGHT,

                'bottom_right',
                'bottomright',
                'right-bottom',
                'right_bottom',
                'rightbottom' => self::BOTTOM_RIGHT,

                'bottom-center',
                'bottom_center',
                'bottomcenter',
                'center-bottom',
                'center_bottom',
                'centerbottom',
                'bottom-middle',
                'bottom_middle',
                'bottommiddle',
                'middle-bottom',
                'middle_bottom',
                'middlebottom' => self::BOTTOM,

                'bottom_left',
                'bottomleft',
                'left-bottom',
                'left_bottom',
                'leftbottom' => self::BOTTOM_LEFT,

                'left-center',
                'left_center',
                'leftcenter',
                'center-left',
                'center_left',
                'centerleft',
                'left-middle',
                'left_middle',
                'leftmiddle',
                'middle-left',
                'middle_left',
                'middleleft' => self::LEFT,

                'top_left',
                'topleft',
                'left-top',
                'left_top',
                'lefttop' => self::TOP_LEFT,

                'middle',
                'center-center',
                'center_center',
                'centercenter',
                'center-middle',
                'center_middle',
                'centermiddle',
                'middle-center',
                'middle_center',
                'middlecenter' => self::CENTER,

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

    /**
     * Change the current alignment by adjusting only the horizontal axis to the specified value.
     */
    public function alignHorizontally(string|self $alignment): self
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
        if (in_array($alignment, [self::CENTER, self::TOP, self::BOTTOM])) {
            return match ($this) {
                self::TOP, self::TOP_RIGHT, self::TOP_LEFT => self::TOP,
                self::BOTTOM, self::BOTTOM_RIGHT, self::BOTTOM_LEFT => self::BOTTOM,
                self::CENTER, self::LEFT, self::RIGHT => self::CENTER,
            };
        }

        return $this;
    }

    /**
     * Change the current alignment by adjusting only the vertical axis to the specified value.
     */
    public function alignVertically(string|self $alignment): self
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
        if (in_array($alignment, [self::CENTER, self::RIGHT, self::LEFT])) {
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
