<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SiamoService
{
    protected $apiUrl;
    protected $apiKey;
    protected $provinceId;

    public function __construct()
    {
        $this->apiUrl = config('siamo.api_url');
        $this->apiKey = config('siamo.api_key');
        $this->provinceId = config('siamo.province_id');
    }

    protected function sendRequest($endpoint, $payload)
    {
        $response = Http::withHeaders([
                'authorization' => $this->apiKey,
                'Accept'        => 'application/json',
            ])
            ->asForm()
            ->post($this->apiUrl . $endpoint, $payload);

        return $response->json();
    }

    public function getMemberRecap($kabupaten)
    {
        $payload = [
            'method'    => 'rekap',
            'provinsi'  => $this->provinceId,
            'kabupaten' => $kabupaten,
        ];

        $main = $this->sendRequest('/getApiMIS/rekap_anggota', $payload);
        $sub  = $this->sendRequest('/getApiMIS/rekap_sub_anggota', $payload);

        return [
            'main' => $main,
            'sub'  => $sub,
        ];
    }


    public function formatMemberData($main, $sub)
    {
        $mainCategories = ['PMR', 'KSR', 'TSR'];
        $resultData = [];

        if (!empty($main['data'])) {
            foreach ($mainCategories as $parentName) {
                if (isset($main['data'][$parentName])) {
                    $count = $main['data'][$parentName];
                    $categories = [];
                    $id = null;

                    if (!empty($sub['data'])) {
                        foreach ($sub['data'] as $subCat) {
                            if ($subCat['parent'] === $parentName) {
                                if ($id === null && isset($subCat['id_kategori'])) {
                                    $id = $subCat['id_kategori'];
                                }
                                $categories[] = [
                                    'name' => $subCat['kategori'],
                                    'count' => (int)$subCat['jumlah'],
                                ];
                            }
                        }
                    }

                    $resultData[] = [
                        'id' => $id,
                        'name' => $parentName,
                        'count' => (int)$count,
                        'categories' => $categories,
                    ];
                }
            }
        }

        return $resultData;
    }

    public function getOffice($city, $query)
    {
        $payload = [
            'method'    => 'search',
            'provinsi'  => $this->provinceId,
            'kabupaten' => $city,
            'markas' => true,
            'query' => $query
        ];

        return $this->sendRequest('/getApiMIS/search_markas', $payload);
    }

    public function formatOfficeData($raw, $type)
    {
        if (!isset($raw['data']) || !is_array($raw['data'])) {
            return [];
        }

        $offices = $raw['data'];
        $filtered = [];

        if ($type === '1') {
            $allowedLevels = ['KABUPATEN', 'KOTA'];
        } elseif ($type === '2') {
            $allowedLevels = ['KECAMATAN'];
        } else {
            $allowedLevels = ['KABUPATEN', 'KOTA', 'KECAMATAN'];
        }

        foreach ($offices as $office) {
            if (!in_array(strtoupper($office['akses_level'] ?? ''), $allowedLevels)) {
                continue;
            }

            $filtered[] = [
                'id' => (int) $office['id'] ?? null,
                'name' => $office['markas'] ?? $office['nama'],
                'phone' => $office['no_telpon'] ?? null,
                'email' => $office['email'] ?? '',
                'locaion' => [
                    'province'=> $office['nama_provinsi'] ?? '',
                    'city'  => $office['nama_kabupaten'] ?? '',
                    'address' => $office['alamat'] ?? '',
                    'coordinate' => [
                        'longitude' => $office['long'] ?? null,
                        'latitude' => $office['lat'] ?? null,
                    ]
                ],
                'type' => [
                    'levelId' => (int) $office['id_level'] ?? null,
                    'name' => $office['akses_level'] ?? '',
                ],
            ];
        }
        return $filtered;
    }
}
