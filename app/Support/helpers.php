<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

if (! function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        return cache()->rememberForever("settings.$key", function () use ($key, $default) {
            $setting = Setting::query()
                ->where('key', $key)
                ->first();

            if (! $setting) {
                return $default;
            }

            if ($setting->type === 'file' && $setting->value) {
                return Storage::disk('public')->url($setting->value);
            }

            return $setting->value ?? $default;
        });
    }
}
