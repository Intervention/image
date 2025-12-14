<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers;

use Intervention\Image\Config;
use Intervention\Image\Exceptions\DecoderException;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\NotSupportedDriverFeatureException;
use Intervention\Image\Exceptions\NotSupportedException;
use Intervention\Image\Exceptions\RuntimeException;
use Intervention\Image\InputHandler;
use Intervention\Image\Interfaces\AnalyzerInterface;
use Intervention\Image\Interfaces\ColorInterface;
use Intervention\Image\Interfaces\DecoderInterface;
use Intervention\Image\Interfaces\DriverInterface;
use Intervention\Image\Interfaces\EncoderInterface;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Interfaces\ModifierInterface;
use Intervention\Image\Interfaces\SpecializableInterface;
use Intervention\Image\Interfaces\SpecializedInterface;
use ReflectionClass;

abstract class AbstractDriver implements DriverInterface
{
    /**
     * @throws DriverException
     */
    public function __construct(protected Config $config = new Config())
    {
        $this->checkHealth();
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::config()
     */
    public function config(): Config
    {
        return $this->config;
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::handleInput()
     *
     * @throws DriverException|DecoderException|NotSupportedException|RuntimeException
     */
    public function handleInput(mixed $input, ?array $decoders = null): ImageInterface|ColorInterface
    {
        if ($decoders === null) {
            return InputHandler::withAllDecoders(driver: $this)->handle($input);
        }

        return InputHandler::withDecoders($decoders, $this)->handle($input);
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::handleImageInput()
     */
    public function handleImageInput(mixed $input, ?array $decoders = null): ImageInterface
    {
        $handler = is_array($decoders) ?
            InputHandler::withDecoders($decoders, $this) :
            InputHandler::withImageDecoders($this);

        $result = $handler->handle($input);

        if (!($result instanceof ImageInterface)) {
            throw new DecoderException('Result must be instance of ' . ImageInterface::class);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::handleColorInput()
     *
     * @throws DriverException|DecoderException|NotSupportedException|RuntimeException
     */
    public function handleColorInput(mixed $input, ?array $decoders = null): ColorInterface
    {
        $handler = is_array($decoders) ?
            InputHandler::withDecoders($decoders, $this) :
            InputHandler::withColorDecoders($this);

        $result = $handler->handle($input);

        if (!($result instanceof ColorInterface)) {
            throw new DecoderException('Result must be instance of ' . ColorInterface::class);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::specializeModifier()
     *
     * @throws NotSupportedException
     */
    public function specializeModifier(ModifierInterface $modifier): ModifierInterface
    {
        return $this->specialize($modifier);
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::specializeAnalyzer()
     *
     * @throws NotSupportedException
     */
    public function specializeAnalyzer(AnalyzerInterface $analyzer): AnalyzerInterface
    {
        return $this->specialize($analyzer);
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::specializeEncoder()
     *
     * @throws NotSupportedException
     */
    public function specializeEncoder(EncoderInterface $encoder): EncoderInterface
    {
        return $this->specialize($encoder);
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::specializeDecoder()
     *
     * @throws NotSupportedException
     */
    public function specializeDecoder(DecoderInterface $decoder): DecoderInterface
    {
        return $this->specialize($decoder);
    }

    /**
     * @throws NotSupportedException
     */
    private function specialize(
        ModifierInterface|AnalyzerInterface|EncoderInterface|DecoderInterface $object
    ): ModifierInterface|AnalyzerInterface|EncoderInterface|DecoderInterface {
        // return object directly if no specializing is possible
        if (!($object instanceof SpecializableInterface)) {
            return $object;
        }

        // return directly and only attach driver if object is already specialized
        if ($object instanceof SpecializedInterface) {
            $object->setDriver($this);

            return $object;
        }

        // resolve classname for specializable object
        $specialized_classname = implode("\\", [
            (new ReflectionClass($this))->getNamespaceName(), // driver's namespace
            match (true) {
                $object instanceof ModifierInterface => 'Modifiers',
                $object instanceof AnalyzerInterface => 'Analyzers',
                $object instanceof EncoderInterface => 'Encoders',
                $object instanceof DecoderInterface => 'Decoders',
            },
            $object_shortname = (new ReflectionClass($object))->getShortName(),
        ]);

        // fail if driver specialized classname does not exists
        if (!class_exists($specialized_classname)) {
            // NEWEX
            throw new NotSupportedDriverFeatureException(
                "Class '" . $object_shortname . "' is not supported by " . $this->id() . " driver"
            );
        }

        // create a driver specialized object with the specializable properties of generic object
        $specialized = new $specialized_classname(...$object->specializable());

        // attach driver
        return $specialized->setDriver($this);
    }
}
