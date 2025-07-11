<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BmkgService
{
    protected $apiEarthquakeUrl;

    public function __construct()
    {
        $this->apiEarthquakeUrl = config('bmkg.api_earthquake_url');
    }

    protected function sendRequest($endpoint)
    {
        $response = Http::withHeaders([
                'Accept' => 'application/json',
            ])
            ->asForm()
            ->get($this->apiEarthquakeUrl . $endpoint);

        return $response->json();
    }

    // Perbaiki typo nama method!
    public function getLatestEarthquake()
    {
        return $this->sendRequest('/autogempa.json');
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
}
