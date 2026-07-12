<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingService
{
    public function all(): array
    {
        return cache()->rememberForever('settings.all', function () {
            return Setting::query()
                ->get()
                ->mapWithKeys(function ($setting) {
                    $value = $setting->value;

                    if ($setting->type === 'file' && $value) {
                        $value = Storage::disk('public')->url($value);
                    }

                    return [$setting->key => $value];
                })
                ->toArray();
        });
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->all()[$key] ?? $default;
    }

    public function whatsappNumber(): ?string
    {
        return $this->get('whatsapp_number');
    }
}
