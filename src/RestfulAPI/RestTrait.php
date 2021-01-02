<?php

namespace MElaraby\Emerald\RestfulAPI;

use Illuminate\Http\Request;

trait RestTrait
{
    /**
     * Determines if request is an api call.
     * If the request URI contains '/api'.
     *
     * @param Request $request
     * @return bool
     */
    protected function isApiCall(Request $request): bool
    {
        return mb_strpos($request->url(), $request->getHost().'/api') !== false;
    }

    /**
     * Determine if request is an Admin call.
     * If the request URI contains '/admin'.
     *
     * @param Request $request
     * @return bool
     */
    protected function isAdminCall(Request $request): bool
    {
        return mb_strpos($request->url(), $request->getHost().'/admin')  !== false;
    }
}
