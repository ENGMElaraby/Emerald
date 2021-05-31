<?php

namespace MElaraby\Emerald\Traits;

use BadMethodCallException;
use Closure;

trait Macroable
{
    /**
     * The registered string macros.
     *
     * @var array
     */
    private static $macros;

    /**
     * Register a custom macro.
     *
     * @param string $name
     * @param object|callable $macro
     * @return void
     */
    public static function macro(string $name, callable $macro)
    {
        static::$macros[$name] = $macro;
    }

    /**
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        if (!static::hasMacro($method)) {
            throw new BadMethodCallException("Method {$method} does not exist.");
        }

        if (static::$macros[$method] instanceof Closure) {
            return call_user_func_array(static::$macros[$method]->bindTo(self::class, static::class), $parameters);
        }

        return call_user_func_array(static::$macros[$method], $parameters);
    }

    /**
     * @param $method
     * @param $parameters
     * @return mixed
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

    /**
     * Checks if macro is registered.
     *
     * @param string $method
     * @return bool
     */
    private static function hasMacro(string $method): bool
    {
        return in_array($method, static::$macros);
    }
}
