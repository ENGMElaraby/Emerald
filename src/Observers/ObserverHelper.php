<?php

namespace MElaraby\Emerald\Observers;


trait ObserverHelper
{
    /**
     * @param string $guard
     * @return int|null
     */
    public function getUserId(string $guard = 'sanctum'): ?int
    {
        return auth($guard)->id();
    }
}
