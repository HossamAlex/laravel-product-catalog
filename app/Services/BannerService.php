<?php

namespace App\Services;

use App\Models\Banner;

class BannerService
{
    public function active()
    {
        return Banner::query()
            ->where('status', true)
            ->where(function ($query) {
                $query->whereNull('start_at')
                    ->orWhere('start_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_at')
                    ->orWhere('end_at', '>=', now());
            })
            ->orderBy('sort')
            ->get();
    }
}