<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Services\BmkgService;
use App\Http\Controllers\Controller;

class BmkgServiceController extends Controller
{
    protected $bmkgService;

    public function __construct(BmkgService $bmkgService)
    {
        $this->bmkgService = $bmkgService;
    }

    public function latestEarthquake(Request $request)
    {
        try {
            $result = $this->bmkgService->getLatestEarthquake();
            if (!$result || !isset($result['Infogempa']['gempa'])) {
                return Response::error(null, 'Data gempa tidak ditemukan', 404);
            }
            $data = $this->bmkgService->formatLatestEarthquake($result);
            return Response::success($data, 'Latest earthquake data retrieved successfully');
        } catch (\Exception $e) {
            return Response::error(null, $e->getMessage(), 500);
        }
    }
}
