<?php

declare(strict_types=1);

namespace Intervention\Image;

use Intervention\Image\Exceptions\InvalidArgumentException;
use Intervention\Image\Interfaces\DataUriInterface;
use Stringable;

class DataUri implements DataUriInterface, Stringable
{
    protected const PATTERN = "/^data:(?P<mediaType>\w+\/[-+.\w]+)?" .
        "(?P<parameters>(;[-\w]+=[-\w]+)*)(?P<base64>;base64)?,(?P<data>.*)/";

    /**
     * Media type of data uri output
     */
    protected ?string $mediaType = null;

    /**
     * Parameters of data uri output
     *
     * @var array<string, string>
     */
    protected array $parameters = [];

    /**
     * Create new data uri instanceof
     *
     * @param array<string, string> $parameters
     */
    public function __construct(
        protected string|Stringable $data = '',
        null|string|MediaType $mediaType = null,
        array $parameters = [],
        protected bool $isBase64Encoded = false
    ) {
        $this->setMediaType($mediaType);
        $this->setParameters($parameters);
    }

    /**
     * {@inheritdoc}
     *
     * @see DataUriInterface::decode()
     */
    public static function decode(string|Stringable $dataUriScheme): self
    {
        $result = preg_match(self::PATTERN, (string) $dataUriScheme, $matches);

        if ($result === false || $result === 0) {
            throw new InvalidArgumentException('Unable to decode data uri scheme from string');
        }

        $isBase64Encoded = $matches['base64'] !== '';

        $datauri = new self(
            data: $isBase64Encoded ? base64_decode($matches['data'], strict: true) : rawurldecode($matches['data']),
            mediaType: $matches['mediaType'],
            isBase64Encoded: $isBase64Encoded,
        );

        if ($matches['parameters'] !== '') {
            $parameters = explode(';', $matches['parameters']);
            $parameters = array_filter($parameters, fn(string $value): bool => $value !== '');
            $parameters = array_map(fn(string $value): array => explode('=', $value), $parameters);
            foreach ($parameters as $parameter) {
                $datauri->setParameter(...$parameter);
            }
        }

        return $datauri;
    }

    /**
     * {@inheritdoc}
     *
     * @see DataUriInterface::create()
     */
    public static function create(
        string $data,
        null|string|MediaType $mediaType = null,
        array $parameters = [],
    ): self {
        return new self(
            data: $data,
            mediaType: $mediaType,
            parameters: $parameters,
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see DataUriInterface::createBase64Encoded()
     */
    public static function createBase64Encoded(
        string $data,
        null|string|MediaType $mediaType = null,
        array $parameters = [],
    ): self {
        return new self(
            data: base64_encode($data),
            mediaType: $mediaType,
            parameters: $parameters,
            isBase64Encoded: true
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see DataUriInterface::data()
     */
    public function data(): string
    {
        return (string) $this->data;
    }

    /**
     * {@inheritdoc}
     *
     * @see DataUriInterface::setData()
     */
    public function setData(string|Stringable $data): self
    {
        $this->data = (string) $data;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see DataUriInterface::mediaType()
     */
    public function mediaType(): ?string
    {
        return $this->mediaType;
    }

    /**
     * {@inheritdoc}
     *
     * @see DataUriInterface::setMediaType()
     */
    public function setMediaType(null|string|MediaType $mediaType): self
    {
        $this->mediaType = $mediaType instanceof MediaType ? $mediaType->value : $mediaType;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see DataUriInterface::parameters()
     */
    public function parameters(): array
    {
        return $this->parameters;
    }

    /**
     * {@inheritdoc}
     *
     * @see DataUriInterface::setParameters()
     */
    public function setParameters(array $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see DataUriInterface::appendParameters()
     */
    public function appendParameters(array $parameters): self
    {
        foreach ($parameters as $key => $value) {
            $this->setParameter((string) $key, (string) $value);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see DataUriInterface::parameter()
     */
    public function parameter(string $key): ?string
    {
        return $this->parameters[$key] ?? null;
    }

    /**
     * {@inheritdoc}
     *
     * @see DataUriInterface::setParameter()
     */
    public function setParameter(string $key, string $value): self
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see DataUriInterface::charset()
     */
    public function charset(): ?string
    {
        return $this->parameter('charset');
    }

    /**
     * {@inheritdoc}
     *
     * @see DataUriInterface::setCharset()
     */
    public function setCharset(string $charset): self
    {
        $this->setParameter('charset', $charset);

        return $this;
    }

    /**
     * Prepare data for output
     */
    private function encodedData(): string
    {
        return $this->isBase64Encoded ? (string) $this->data : rawurlencode((string) $this->data);
    }

    /**
     * Prepare all set parameters for output
     */
    private function encodedParameters(): string
    {
        if (count($this->parameters) === 0 && $this->isBase64Encoded === false) {
            return '';
        }

        $parameters = array_map(function (mixed $key, mixed $value) {
            return $key . '=' . $value;
        }, array_keys($this->parameters), $this->parameters);

        $parameterString = count($parameters) ? ';' . implode(';', $parameters) : '';

        if ($this->isBase64Encoded) {
            $parameterString .= ';base64';
        }

        return $parameterString;
    }

    /**
     * {@inheritdoc}
     *
     * @see DataUriInterface::toString()
     */
    public function toString(): string
    {
        return 'data:' . $this->mediaType() . $this->encodedParameters() . ',' . $this->encodedData();
    }

    /**
     * {@inheritdoc}
     *
     * @see Stringable::__toString()
     */
    public function __toString(): string
    {
        return $this->toString();
    }
}
