<?php

namespace MElaraby\Emerald\Traits;

use Closure;
use BadMethodCallException;

trait Macroable
{
    private static $macros;

    /**
     * @param $name
     * @param callable $macro
     */
    public static function macro($name, callable $macro)
    {
        static::$macros[$name] = $macro;
    }

    /**
     * @param $method
     * @return bool
     */
    private static function hasMacro($method): bool
    {
        return in_array($method, static::$macros);
    }

    /**
     * @param $method
     * @param $parameters
     * @return false|mixed
     */
    public function __call($method, $parameters)
    {
        if (!static::hasMacro($method)) {
            throw new BadMethodCallException("Method {$method} does not exist.");
        }

        if (static::$macros[$method] instanceof Closure) {
            return call_user_func_array(static::$macros[$method]->bindTo($this, static::class), $parameters);
        }

        return call_user_func_array(static::$macros[$method], $parameters);
    }
}
