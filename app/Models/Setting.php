<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    protected static function booted(): void
    {
        static::saved(function (Setting $setting) {
            cache()->forget('settings.all');
            cache()->forget('settings.' . $setting->key);
        });

        static::updated(function (Setting $setting) {
            if ($setting->wasChanged('key')) {
                cache()->forget('settings.' . $setting->getOriginal('key'));
            }
        });

        static::deleted(function (Setting $setting) {
            cache()->forget('settings.all');
            cache()->forget('settings.' . $setting->key);
        });
    }

    public function getDisplayValueAttribute()
    {
        if ($this->type === 'file' && $this->value) {
            return Storage::disk('public')->url($this->value);
        }

        return $this->value;
    }

}
