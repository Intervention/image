<?php

declare(strict_types=1);

namespace Intervention\Image\Typography;

enum UnicodeBlock
{
    case LATIN;
    case ARABIC;
    case CHINESE;
    case JAPANESE;
    case KOREAN;
    case THAI;

    public static function fromString(string $string): self
    {
        return match (1) {
            preg_match('/[\x{0600}-\x{06FF}' . '\x{0750}-\x{077F}' . '\x{08A0}-\x{08FF}]/u', $string) => self::ARABIC,
            preg_match('/[\x{3040}-\x{309F}' . '\x{30A0}-\x{30FF}]/u', $string) => self::JAPANESE,
            preg_match('/[\x{4E00}-\x{9FFF}]/u', $string) => self::CHINESE,
            preg_match('/[\x{AC00}-\x{D7AF}]/u', $string) => self::KOREAN,
            preg_match('/[\x{0E00}-\x{0E7F}]/u', $string) => self::THAI,
            default => self::LATIN,
        };
    }
}
