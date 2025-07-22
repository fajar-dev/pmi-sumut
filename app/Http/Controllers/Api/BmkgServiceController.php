<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Response;
use Illuminate\Http\Request;
use App\Services\BmkgService;
use App\Http\Controllers\Controller;

class BmkgServiceController extends Controller
{
    protected $bmkgService;
    protected $defaultWeatherLatitude;
    protected $defaultWeatherLongitude;

    public function __construct(BmkgService $bmkgService)
    {
        $this->bmkgService = $bmkgService;
        $this->defaultWeatherLatitude = config('bmkg.default_weather_latitude');
        $this->defaultWeatherLongitude = config('bmkg.default_weather_longitude');
    }

    public function latestEarthquake(Request $request)
    {
        try {
            $result = $this->bmkgService->getLatestEarthquake();
            if (!$result || !isset($result['Infogempa']['gempa'])) {
                return Response::notFound('No earthquake data found');
            }
            $data = $this->bmkgService->formatLatestEarthquake($result);
            return Response::success($data, 'Latest earthquake data retrieved successfully');
        } catch (\Exception $e) {
            return Response::error(null, $e->getMessage(), 500);
        }
    }

    public function currentWeather(Request $request)
    {
        $longitude = $request->input('longitude', $this->defaultWeatherLongitude);
        $latitude = $request->input('latitude', $this->defaultWeatherLatitude);

        try {
            $result = $this->bmkgService->getCurrentWeather($longitude, $latitude);
            if (!$result || !isset($result['data'])) {
                return Response::notFound('No weather data found');
            }
            $data = $this->bmkgService->formatWeatherCurrent($result);
            return Response::success($data, 'Current weather data retrieved successfully');
        } catch (\Exception $e) {
            return Response::error(null, $e->getMessage(), 500);
        }
    }

    public function forecastWeather(Request $request)
    {
        $longitude = $request->input('longitude', $this->defaultWeatherLongitude);
        $latitude = $request->input('latitude', $this->defaultWeatherLatitude);
        $index = $request->input('index', null);

        try {
            $result = $this->bmkgService->getForecastWeather($longitude, $latitude);
            if (!$result || !isset($result['data'])) {
                return Response::notFound('No weather forecast data found');
            }
            $data = $this->bmkgService->formatWeatherForecast($result, $index);
            return Response::success($data, 'Weather forecast data retrieved successfully');
        } catch (\Exception $e) {
            return Response::error(null, $e->getMessage(), 500);
        }
    }

    public function earlyWarning(Request $request)
    {
        $longitude = $request->input('longitude', $this->defaultWeatherLongitude);
        $latitude = $request->input('latitude', $this->defaultWeatherLatitude);

        try {
            $result = $this->bmkgService->getEarlyWarning($longitude, $latitude);
            // return $result;
            if (!$result || !isset($result['data']) || empty($result['data']['today'])) {
            return Response::notFound('No early warning data found for today');
        }
            $data = $this->bmkgService->formatEarlyWarning($result);
            return Response::success($data, 'Early warning data retrieved successfully');
        } catch (\Exception $e) {
            return Response::error(null, $e->getMessage(), 500);
        }
    }
}
