<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Services\OpenStreetMapService;
use Illuminate\Http\Request;

class GeoCodeController extends Controller
{
    protected $openStreetMapService;

    public function __construct(OpenStreetMapService $openStreetMapService)
    {
        $this->openStreetMapService = $openStreetMapService;
    }

    public function geoCode(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        if (!$latitude || !$longitude) {
            return Response::badRequest('Latitude and longitude are required');
        }

        try {
            $result = $this->openStreetMapService->getGeoCode($latitude, $longitude);
            $data = $this->openStreetMapService->formatGeoCode($result);
            return Response::success($data, 'Geocode data retrieved successfully');
        } catch (\Exception $e) {
            return Response::error(null, $e->getMessage(), 500);
        }
    }
}
