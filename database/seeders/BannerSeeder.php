<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'title' => 'New Collection',
                'button_text' => 'Shop Now',
                'link' => '/products',
                'sort' => 1,
            ],
            [
                'title' => 'Seasonal Offers',
                'button_text' => 'View Offers',
                'link' => '/collections/offers',
                'sort' => 2,
            ],
            [
                'title' => 'Featured Products',
                'button_text' => 'Explore',
                'link' => '/products',
                'sort' => 3,
            ],
        ];

        foreach ($banners as $index => $data) {
            $banner = Banner::updateOrCreate(
                ['sort' => $data['sort']],
                [
                    'title' => $data['title'],
                    'button_text' => $data['button_text'],
                    'link' => $data['link'],
                    'status' => true,
                    'start_at' => now()->subDay(),
                    'end_at' => now()->addYear(),
                ]
            );

            $desktopImage = storage_path('app/demo/banners/' . ($index + 1) . '.jpg');
            $mobileImage = storage_path('app/demo/banners/mobile-' . ($index + 1) . '.jpg');

            // Filament uses media UUIDs to hydrate existing upload previews.
            $banner->media()
                ->whereNull('uuid')
                ->get()
                ->each(function ($media): void {
                    $media->forceFill(['uuid' => (string) Str::uuid()])->saveQuietly();
                });

            if (file_exists($desktopImage)) {
                $banner->clearMediaCollection('desktop_image');

                $media = $banner
                    ->addMedia($desktopImage)
                    ->preservingOriginal()
                    ->toMediaCollection('desktop_image', 'public');

                $this->ensureMediaHasUuid($media);
            }

            if (file_exists($mobileImage)) {
                $banner->clearMediaCollection('mobile_image');

                $media = $banner
                    ->addMedia($mobileImage)
                    ->preservingOriginal()
                    ->toMediaCollection('mobile_image', 'public');

                $this->ensureMediaHasUuid($media);
            }
        }
    }

    private function ensureMediaHasUuid($media): void
    {
        if (blank($media->uuid)) {
            $media->forceFill(['uuid' => (string) Str::uuid()])->saveQuietly();
        }
    }
}
