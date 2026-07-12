<?php

namespace App\Services;

class HomeService
{
    public function __construct(
        protected BannerService $bannerService,
        protected CategoryService $categoryService,
        protected CollectionService $collectionService,
        protected SettingService $settingService,
    ) {}

    public function data(): array
    {
        return [
            'hero_banners' => $this->bannerService->active(),
            'featured_categories' => $this->categoryService->activeTree(),
            'sections' => $this->collectionService->activeWithProducts(),
            'settings' => $this->settingService->all(),
        ];
    }
}
