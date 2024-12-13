<?php

use Illuminate\Support\Facades\Route;

if (!function_exists('isActiveRoute')) {
    /**
     * Determine if the current route is active.
     *
     * @param string|array $routes
     * @return boolean
     */
    function isActiveRoute($routes)
    {
        if (is_array($routes)) {
            foreach ($routes as $route) {
                if (Route::currentRouteName() === $route) {
                    return true;
                }
            }
        } elseif (Route::currentRouteName() === $routes) {
            return true;
        }

        return false;
    }
}
