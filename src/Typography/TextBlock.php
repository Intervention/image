<?php

declare(strict_types=1);

namespace Intervention\Image\Typography;

use Intervention\Image\Collection;

class TextBlock extends Collection
{
    private $isArabic;
    public function __construct(string $text)
    {
        foreach (explode("\n", $text) as $line) {
            $this->push(new Line($line));
        }
        $this->isArabic = $this->isArabic($text);
    }
    private function isArabic($text) {
        // Regular expression pattern to match Arabic characters
        $pattern = '/\p{Arabic}/u';

        // Check if the text contains Arabic characters
        if (preg_match($pattern, $text)) {
            return true; // Text contains Arabic characters
        } else {
            return false; // Text does not contain Arabic characters
        }
    }

    /**
     * Return array of lines in text block
     *
     * @return array
     */
    public function lines(): array
    {
        return $this->items;
    }

    /**
     * Set lines of the text block
     *
     * @param array $lines
     * @return self
     */
    public function setLines(array $lines): self
    {

        if($this->isArabic)
        {
            $this->items = array_reverse($lines);
        }else{
            $this->items = $lines;
        }
        
        return $this;
    }


    /**
     * Get line by given key
     *
     * @param mixed $key
     * @return null|Line
     */
    public function line($key): ?Line
    {
        if (!array_key_exists($key, $this->lines())) {
            return null;
        }

        return $this->lines()[$key];
    }

    /**
     * Return line with most characters of text block
     *
     * @return Line
     */
    public function longestLine(): Line
    {
        $lines = $this->lines();
        usort($lines, function (Line $a, Line $b) {
            if (mb_strlen((string) $a) === mb_strlen((string) $b)) {
                return 0;
            }
            return mb_strlen((string) $a) > mb_strlen((string) $b) ? -1 : 1;
        });

        return $lines[0];
    }
}
