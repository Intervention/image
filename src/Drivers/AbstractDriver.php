<?php

declare(strict_types=1);

namespace Intervention\Image\Drivers;

use Intervention\Image\Config;
use Intervention\Image\Exceptions\DriverException;
use Intervention\Image\Exceptions\NotSupportedException;
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
     * Driver options
     */
    protected Config $config;

    /**
     * @throws DriverException
     * @return void
     */
    public function __construct()
    {
        $this->config = new Config();
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
     */
    public function handleInput(mixed $input, array $decoders = []): ImageInterface|ColorInterface
    {
        return InputHandler::withDecoders($decoders, $this)->handle($input);
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::specialize()
     */
    public function specialize(
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
            throw new NotSupportedException(
                "Class '" . $object_shortname . "' is not supported by " . $this->id() . " driver."
            );
        }

        // create a driver specialized object with the specializable properties of generic object
        $specialized = new $specialized_classname(...$object->specializable());

        // attach driver
        return $specialized->setDriver($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see DriverInterface::specializeMultiple()
     *
     * @throws NotSupportedException
     * @throws DriverException
     */
    public function specializeMultiple(array $objects): array
    {
        return array_map(
            function (string|object $object): ModifierInterface|AnalyzerInterface|EncoderInterface|DecoderInterface {
                return $this->specialize(
                    match (true) {
                        is_string($object) => new $object(),
                        is_object($object) => $object,
                    }
                );
            },
            $objects
        );
    }
}
