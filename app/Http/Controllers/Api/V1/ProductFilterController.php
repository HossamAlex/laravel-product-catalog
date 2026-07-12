<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\ProductFilterService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ProductFilterController extends Controller
{
    use ApiResponse;

    public function __invoke(Request $request, ProductFilterService $service)
    {
        return $this->success(
            $service->filters($request->get('category'))
        );
    }
}