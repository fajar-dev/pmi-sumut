<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BmkgService
{
    protected $apiEarthquakeUrl;
    protected $apiWeatherUrl;

    public function __construct()
    {
        $this->apiEarthquakeUrl = config('bmkg.api_earthquake_url');
        $this->apiWeatherUrl = config('bmkg.api_weather_url');
    }

    protected function sendRequestEarthquake($endpoint)
    {
        $response = Http::withHeaders([
                'Accept' => 'application/json',
            ])
            ->asForm()
            ->get($this->apiEarthquakeUrl . $endpoint);

        return $response->json();
    }

    protected function sendRequestWeather($endpoint)
    {
        $response = Http::withHeaders([
                'Accept' => 'application/json',
            ])
            ->asForm()
            ->get($this->apiWeatherUrl . $endpoint);

        return $response->json();
    }

    public function getLatestEarthquake()
    {
        return $this->sendRequestEarthquake('/autogempa.json');
    }

    public function formatLatestEarthquake($raw)
    {
        $g = $raw['Infogempa']['gempa'] ?? [];

        return [
            'date'        => $g['Tanggal'] ?? null,
            'time'        => $g['Jam'] ?? null,
            'datetime'    => $g['DateTime'] ?? null,
            'coordinates' => $g['Coordinates'] ?? null,
            'latitude'    => $g['Lintang'] ?? null,
            'longitude'   => $g['Bujur'] ?? null,
            'magnitude'   => $g['Magnitude'] ?? null,
            'depth'       => $g['Kedalaman'] ?? null,
            'region'      => $g['Wilayah'] ?? null,
            'potential'   => $g['Potensi'] ?? null,
            'felt'        => $g['Dirasakan'] ?? null,
            'shakemap'    => 'https://static.bmkg.go.id/'.$g['Shakemap'] ?? null,
        ];
    }

    public function getCurrentWeather($longitude, $latitude)
    {
        return $this->sendRequestWeather('/presentwx/coord?lon=' . $longitude . '&lat=' . $latitude);
    }

    public function formatWeatherCurrent($raw)
    {
        $g = $raw['data'] ?? [];

        return [
            'administrativeAreaLevel1' => [
                'id'    => $g['lokasi']['adm1'] ?? null,
                'name'  => $g['lokasi']['provinsi'] ?? null,
            ],
            'administrativeAreaLevel2' => [
                'id'    => $g['lokasi']['adm2'] ?? null,
                'name'  => $g['lokasi']['kabupaten'] ?? null,
            ],
            'administrativeAreaLevel3' => [
                'id'    => $g['lokasi']['adm3'] ?? null,
                'name'  => $g['lokasi']['kecamatan'] ?? null,
            ],
            'administrativeAreaLevel4' => [
                'id'    => $g['lokasi']['adm4'] ?? null,
                'name'  => $g['lokasi']['desa'] ?? null,
            ],
            'coordinate' => [
                'latitude'  => $g['lokasi']['lat'] ?? null,
                'longitude' => $g['lokasi']['lon'] ?? null,
            ],
            'weather' => [
                'weatherId'     => $g['cuaca']['weather'] ?? null,
                'icon'          => $g['cuaca']['image'] ?? null,
                'description'   => [
                    'id'    => $g['cuaca']['weather_desc'] ?? null,
                    'en'  => $g['cuaca']['weather_desc_en'] ?? null,
                ],
                'dateTime' => [
                    'wib' => $g['cuaca']['local_datetime'] ?? null,
                    'utc' => $g['cuaca']['datetime'] ?? null,
                ],
                'temperature' =>$g['cuaca']['t'] ?? null,
                'humidity' => $g['cuaca']['hu'].'%' ?? null,
                'wind' => [
                    'speed'     => $g['cuaca']['ws'].' km/jam' ?? null,
                    'direction' => $g['cuaca']['wd'] ?? null,
                ],
                'cloudiness' => $g['cuaca']['tcc'].'%' ?? null,
                'visibility' => $g['cuaca']['vs_text'] ?? null,
            ],
        ];
    }

    public function getForecastWeather($longitude, $latitude)
    {
        return $this->sendRequestWeather('/df/v1/forecast/coord?lon=' . $longitude . '&lat=' . $latitude);
    }

    public function formatWeatherForecast($raw)
    {
        $g = $raw['data'][0] ?? [];

        return [
            'administrativeAreaLevel1' => [
                'id'    => $g['lokasi']['adm1'] ?? null,
                'name'  => $g['lokasi']['provinsi'] ?? null,
            ],
            'administrativeAreaLevel2' => [
                'id'    => $g['lokasi']['adm2'] ?? null,
                'name'  => $g['lokasi']['kotkab'] ?? null,
            ],
            'administrativeAreaLevel3' => [
                'id'    => $g['lokasi']['adm3'] ?? null,
                'name'  => $g['lokasi']['kecamatan'] ?? null,
            ],
            'administrativeAreaLevel4' => [
                'id'    => $g['lokasi']['adm4'] ?? null,
                'name'  => $g['lokasi']['desa'] ?? null,
            ],
            'coordinate' => [
                'latitude'  => $g['lokasi']['lat'] ?? null,
                'longitude' => $g['lokasi']['lon'] ?? null,
            ],
            'weather' => array_map(function ($forecast) {
            return [
                'weatherId'     => $forecast['weather'] ?? null,
                'icon'          => $forecast['image'] ?? null,
                'description'   => [
                    'id' => $forecast['weather_desc'] ?? null,
                    'en' => $forecast['weather_desc_en'] ?? null,
                ],
                'dateTime' => [
                    'wib' => $forecast['local_datetime'] ?? null,
                    'utc' => $forecast['datetime'] ?? null,
                ],
                'temperature'   => $forecast['t'] ?? null,
                'humidity'      => isset($forecast['hu']) ? $forecast['hu'] . '%' : null,
                'wind' => [
                    'speed'     => isset($forecast['ws']) ? $forecast['ws'] . ' km/jam' : null,
                    'direction' => $forecast['wd'] ?? null,
                ],
                'cloudiness'    => isset($forecast['tcc']) ? $forecast['tcc'] . '%' : null,
                'visibility'    => $forecast['vs_text'] ?? null,
            ];
        }, $g['cuaca'][0] ?? []),
        ];
    }
}
