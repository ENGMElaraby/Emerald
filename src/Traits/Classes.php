<?php


namespace MElaraby\Emerald\Traits;


use ReflectionClass;
use ReflectionException;

trait Classes
{
    /**
     * @param string $class
     * @return ReflectionClass
     */
    private static function getClassInformation(string $class): ReflectionClass
    {
        return new ReflectionClass($class);
    }

    /**
     * @param string $class
     * @return string
     */
    private static function getClassName(string $class): string
    {
        return static::getClassInformation($class)->getShortName();
    }
}
