<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenStreetMapService
{
    protected $apiOpenStreetMapUrl;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiOpenStreetMapUrl = config('openstreetmap.api_openstreetmap_url');
        $this->baseUrl = config('app.url');
    }

    protected function sendRequest($endpoint)
    {
        $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Referer' => $this->baseUrl,
            ])
            ->get($this->apiOpenStreetMapUrl . $endpoint);
            
        return $response->json();
    }

    public function getGeoCode($latitude, $longitude)
    {
        return $this->sendRequest('/reverse?lat='.$latitude.'&lon='.$longitude.'&format=json');
    }

    public function formatGeoCode($raw)
    {
        return [
            'placeId'      => $raw['place_id'] ?? null,
            'latitude'     => $raw['lat'] ?? null,
            'longitude'    => $raw['lon'] ?? null,
            'name'         => $raw['name'] ?? null,
            'displayName' => $raw['display_name'] ?? null,
            'address'      => $raw['address'] ?? null,
        ];
    }
}
