<?php

// Mockproxy which can be used to mock static method calls
// Taken from: https://www.deanspot.org/alex/2011/10/25/mocking-static-method-calls-phpunit.html
class MockProxy 
{
    private static $mock;

    public static function setStaticExpectations($mock) 
    {
        self::$mock = $mock;
    }

    // Any static calls we get are passed along to self::$mock. public static
    public static function __callStatic($name, $args) 
    {
        return call_user_func_array(array(self::$mock,$name), $args);
    }
}