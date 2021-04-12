<?php

namespace MElaraby\Emerald\Helpers;


trait AuthHelper
{
    /**
     * check Auth logged in or not
     *
     * @param string|null $guard
     * @return bool
     */
    private function checkAuth(?string $guard = null): bool
    {
        return auth()->guard($guard)->check();
    }
}
