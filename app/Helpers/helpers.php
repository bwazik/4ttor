<?php

use Illuminate\Support\Facades\Route;

if (!function_exists('isActiveRoute')) {
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

if (!function_exists('pageTitle')) {
    function pageTitle($key) {
        return trans('layouts/sidebar.platformName') . ' - ' . trans($key);
    }
}

if (!function_exists('formatCurrency')) {
    function formatCurrency($value) {
        return number_format($value, 2, '.', ',');
    }
}



