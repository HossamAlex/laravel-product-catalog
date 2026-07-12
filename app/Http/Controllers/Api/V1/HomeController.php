<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CollectionResource;
use App\Services\HomeService;
use App\Traits\ApiResponse;
class HomeController extends Controller
{
    use ApiResponse;
    public function __invoke(HomeService $homeService)
    {
        $data = $homeService->data();

        return $this->success([
            'hero_banners' => BannerResource::collection($data['hero_banners']),
            'featured_categories' => CategoryResource::collection($data['featured_categories']),
            'sections' => CollectionResource::collection($data['sections']),
            'settings' => $data['settings'],
        ]);
    }

}
