<?php

namespace Intervention\Image\Commands;

class Argument
{
    public $command;
    public $key;

    public function __construct(AbstractCommand $command, $key = 0)
    {
        $this->command = $command;
        $this->key = $key;
    }

    public function getCommandName()
    {
        preg_match("/\\\\([\w]+)Command$/", get_class($this->command), $matches);
        return isset($matches[1]) ? lcfirst($matches[1]).'()' : 'Method';
    }

    public function value($default = null)
    {
        $arguments = $this->command->arguments;

        if (is_array($arguments)) {
            return array_key_exists($this->key, $arguments) ? $arguments[$this->key] : $default;
        }
    }

    public function required()
    {
        if ( ! array_key_exists($this->key, $this->command->arguments)) {
            throw new \Intervention\Image\Exception\InvalidArgumentException(
                sprintf("Missing argument %d for %s", $this->key + 1, $this->getCommandName())
            );
        }

        return $this;
    }

    public function type($type)
    {
        $fail = false;

        $value = $this->value();

        if (is_null($value)) {
            return $this;
        }

        switch (strtolower($type)) {
            
            case 'bool':
            case 'boolean':
                $fail =  ! is_bool($value);
                $message = sprintf('%s accepts only boolean values as argument %d.', $this->getCommandName(), $this->key + 1);
                break;

            case 'int':
            case 'integer':
                $fail =  ! is_integer($value);
                $message = sprintf('%s accepts only integer values as argument %d.', $this->getCommandName(), $this->key + 1);
                break;

            case 'num':
            case 'numeric':
                $fail =  ! is_numeric($value);
                $message = sprintf('%s accepts only numeric values as argument %d.', $this->getCommandName(), $this->key + 1);
                break;

            case 'str':
            case 'string':
                $fail =  ! is_string($value);
                $message = sprintf('%s accepts only string values as argument %d.', $this->getCommandName(), $this->key + 1);
                break;

            case 'closure':
                $fail =  ! is_a($value, '\Closure');
                $message = sprintf('%s accepts only Closure as argument %d.', $this->getCommandName(), $this->key + 1);
                break;
        }

        if ($fail) {

            $message = isset($message) ? $message : sprintf("Missing argument for %d.", $this->key);

            throw new \Intervention\Image\Exception\InvalidArgumentException(
                $message
            );
        }

        return $this;
    }

    public function between($x, $y)
    {
        $value = $this->type('numeric')->value();

        if (is_null($value)) {
            return $this;
        }

        $alpha = min($x, $y);
        $omega = max($x, $y);

        if ($value < $alpha || $value > $omega) {
            throw new \Intervention\Image\Exception\InvalidArgumentException(
                sprintf('Argument %d must be between %s and %s.', $this->key, $x, $y)
            );
        }

        return $this;
    }
}
