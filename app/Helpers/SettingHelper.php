<?php

use App\Models\Settings;

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        return Settings::where('key', $key)->value('value') ?? $default;
    }
}
