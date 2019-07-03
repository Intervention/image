<?php


namespace Intervention\Image;

use Intervention\Image\Exception\InvalidArgumentException;
use Intervention\Image\Exception\RuntimeException;
use JsonSerializable;

/**
 * Resolution of an image.
 */
class Resolution implements JsonSerializable
{
    const UNITS_PPI     = 'ppi';
    const UNITS_PPCM    = 'ppcm';
    const UNITS_UNKNOWN = 'unknown';

    /**
     * @var int
     */
    protected $x;

    /**
     * @var int
     */
    protected $y;

    /**
     * @var string
     */
    protected $units;

    public function __construct(int $x, int $y, string $units = self::UNITS_PPI)
    {
        $this->x     = $x;
        $this->y     = $y;
        $this->units = $this->units($units);
    }

    // <editor-fold desc="API">
    // =========================================================================
    public function getX(string $units = null): int
    {
        return $this->convert($this->x, $this->getUnits(), $units);
    }

    public function setX(int $x, string $units = null): self
    {
        $this->x = $this->convert($x, $units, $this->getUnits());

        return $this;
    }

    public function getY(string $units = null): int
    {
        return $this->convert($this->y, $this->getUnits(), $units);
    }

    public function setY(int $y, string $units = null): self
    {
        $this->y = $this->convert($y, $units, $this->getUnits());

        return $this;
    }

    public function getUnits(): string
    {
        return $this->units;
    }

    public function hasUnits(string $units): bool
    {
        return $this->units === $units;
    }

    public function setUnits(string $units): self
    {
        $this->x     = $this->getX($units);
        $this->y     = $this->getY($units);
        $this->units = $units;

        return $this;
    }
    // </editor-fold>

    // <editor-fold desc="Functions">
    // =========================================================================
    protected function convert(int $value, ?string $units, ?string $target): int
    {
        // Default
        $units  = $this->units($units ?: $this->getUnits());
        $target = $this->units($target ?: $this->getUnits());

        // Same?
        if ($units === $target) {
            return $value;
        }

        // Convert
        $converted = null;

        switch ($units) {
            case self::UNITS_PPI:
                switch ($target) {
                    case self::UNITS_PPCM:
                        $converted = self::ppi2ppcm($value);
                        break;
                    default:
                        // impossible to convert
                        break;
                }
                break;
            case self::UNITS_PPCM:
                switch ($target) {
                    case self::UNITS_PPI:
                        $converted = self::ppcm2ppi($value);
                        break;
                    default:
                        // impossible to convert
                        break;
                }
                break;
            default:
                // impossible to convert
                break;
        }

        if (is_null($converted)) {
            throw new RuntimeException("Impossible convert value from '{$units}' to '{$target}'.");
        }

        // Return
        return $converted;
    }

    protected function units(string $units): string
    {
        if (!in_array($units, [self::UNITS_PPI, self::UNITS_PPCM, self::UNITS_UNKNOWN], true)) {
            throw new InvalidArgumentException("Units '{$units}' is not supported.");
        }

        return $units;
    }
    // </editor-fold>

    // <editor-fold desc="Arrayable">
    // =========================================================================
    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return [
            'x'     => $this->getX(),
            'y'     => $this->getY(),
            'units' => $this->getUnits(),
        ];
    }
    // </editor-fold>

    // <editor-fold desc="JsonSerializable">
    // =========================================================================
    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
    //</editor-fold>

    // <editor-fold desc="Helpers">
    // =========================================================================
    public static function ppcm2ppi(int $dpcm): int
    {
        return (int)round($dpcm * 2.54);
    }

    public static function ppi2ppcm(int $dpi): int
    {
        return (int)round($dpi / 2.54);
    }
    // </editor-fold>
}
