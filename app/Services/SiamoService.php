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
}
