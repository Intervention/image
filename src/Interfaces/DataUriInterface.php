<?php

declare(strict_types=1);

namespace Intervention\Image\Interfaces;

use Intervention\Image\MediaType;

interface DataUriInterface
{
    /**
     * Create new object from given data uri scheme string
     */
    public static function decode(string $dataUriScheme): self;

    /**
     * Create data uri object from given unencoded data
     *
     * @param array<string, string> $parameters
     */
    public static function create(
        string $data,
        null|string|MediaType $mediaType = null,
        array $parameters = [],
    ): self;

    /**
     * Create base 64 encoded data uri object from given unencoded data
     *
     * @param array<string, string> $parameters
     */
    public static function createBase64Encoded(
        string $data,
        null|string|MediaType $mediaType = null,
        array $parameters = [],
    ): self;

    /**
     * Return current data uri data
     */
    public function data(): string;

    /**
     * Set data of current data uri scheme
     */
    public function setData(string $data): self;

    /**
     * Get media type of current data uri output
     */
    public function mediaType(): ?string;

    /**
     * Set media type of current data uri output
     */
    public function setMediaType(null|string|MediaType $mediaType): self;

    /**
     * Get all parameters of current data uri output
     *
     * @return array<string, string>
     */
    public function parameters(): array;

    /**
     * Set (overwrite) all parameters of current data uri output
     *
     * @param array<string, string> $parameters
     */
    public function setParameters(array $parameters): self;

    /**
     * Append given parameters to current data uri output
     *
     * @param array<string, string> $parameters
     */
    public function appendParameters(array $parameters): self;

    /**
     * Get value of given parameter, return null if parameter is not set
     */
    public function parameter(string $key): ?string;

    /**
     * Set (overwrite) parameter of given key to given value
     */
    public function setParameter(string $key, string $value): self;

    /**
     * Get charset of current data uri scheme, null if no charset is defined
     */
    public function charset(): ?string;

    /**
     * Define charset of current data uri scheme
     */
    public function setCharset(string $charset): self;

    /**
     * Transform current data uri scheme to string
     */
    public function toString(): string;
}
