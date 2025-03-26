<?php

use Carbon\Carbon;
use Illuminate\Support\Str;
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
        return trans($key) . ' - ' . trans('layouts/sidebar.platformName');
    }
}

if (!function_exists('formatCurrency')) {
    function formatCurrency($value) {
        return number_format($value, 2, '.', ',');
    }
}

if(!function_exists('getDayName')) {
    function getDayName(int $dayNumber)
    {
        $dayMapping = [
            1 => trans('main.weekdays.1'),
            2 => trans('main.weekdays.2'),
            3 => trans('main.weekdays.3'),
            4 => trans('main.weekdays.4'),
            5 => trans('main.weekdays.5'),
            6 => trans('main.weekdays.6'),
            7 => trans('main.weekdays.7'),
        ];

        return $dayMapping[$dayNumber] ?? '-';
    }
}

if(!function_exists('mapDaysToNames')) {
    function mapDaysToNames(array $days)
    {
        return array_map(function ($day) {
            return getDayName($day);
        }, $days);
    }
}

if(!function_exists('isoFormat')) {
    function isoFormat(string $value)
    {
        return Carbon::parse($value)->isoFormat('dddd D MMMM h:mm A');
    }
}

if(!function_exists('humanFormat')) {
    function humanFormat(string $value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }
}

if (!function_exists('isAdmin')) {
    function isAdmin()
    {
        return auth()->guard('web')->check();
    }
}

if (!function_exists('filterByRelation')) {
    function filterByRelation($query, $relation, $column, $keyword)
    {
        $query->whereHas($relation, function ($q) use ($column, $keyword) {
            $q->where($column, 'LIKE', "%$keyword%");
        });
    }
}

if (!function_exists('filterByStatus')) {
    function filterByStatus($query, $keyword, $column = 'is_active')
    {
        $keyword = trim(mb_strtolower($keyword, 'UTF-8'));

        $activeKeywords = ['active', 'مفعل'];
        $inactiveKeywords = ['inactive', 'غير', 'غير مفعل'];

        if (Str::contains($keyword, $activeKeywords)) {
            $query->where($column, 1);
        } elseif (Str::contains($keyword, $inactiveKeywords)) {
            $query->where($column, 0);
        }
    }
}

