<?php

namespace App\Services;

use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Http;

class AyodonorService
{
    protected $apiAyodonor;
    protected $provinceId;

    public function __construct()
    {
        $this->apiAyodonor = config('ayodonor.api_url');
        $this->provinceId = config('ayodonor.province_id');
    }

    protected function sendRequest($endpoint, $payload)
    {
        $response = Http::withHeaders([
                'Accept' => 'application/json',
            ])
            ->asForm()
            ->post($this->apiAyodonor . $endpoint, $payload);

        return $response->json();
    }

    public function getBloodStock($bloodDonorUnit)
    {
        $payload = [
            'udd'    => $bloodDonorUnit,
        ];
        return $this->sendRequest('/jmlstokudd.php', $payload);
    }

    public function getMobileUnitSchedule($date, $bloodDonorUnit)
    {
        $payload = [
            'tanggal'=> $date,
            'prov' => $this->provinceId,
            'kota'    => $bloodDonorUnit,
        ];
        return $this->sendRequest('/jadwalmu.php', $payload);
    }

    public function getContact()
    {
        return $this->sendRequest('/getkontak.php', null);
    }

    public function formatBloodStock($bloodDonorUnit, $raw)
    {
      return array_merge(
          (array) $bloodDonorUnit,
          [
              'product' => array_map(function ($product) {
                  return [
                      'name' => $product['produk'] ?? null,
                      'stock' => [
                          [
                              'type' => 'a',
                              'rhesus' => [
                                  'positive' => $product['a_pos'] ?? null,
                                  'negative' => $product['a_neg'] ?? null,
                              ],
                          ],
                          [
                              'type' => 'b',
                              'rhesus' => [
                                  'positive' => $product['b_pos'] ?? null,
                                  'negative' => $product['b_neg'] ?? null,
                              ],
                          ],
                          [
                              'type' => 'ab',
                              'rhesus' => [
                                  'positive' => $product['ab_pos'] ?? null,
                                  'negative' => $product['ab_neg'] ?? null,
                              ],
                          ],
                          [
                              'type' => 'o',
                              'rhesus' => [
                                  'positive' => $product['o_pos'] ?? null,
                                  'negative' => $product['o_neg'] ?? null,
                              ],
                          ],
                      ],
                      'updatedAt' => $product['update_on'] ?? null,
                  ];
              }, $raw['data'] ?? []),
          ]
      );
    }

public function formatMobileUnitSchedule($bloodDonorUnit, $raw)
{
    return array_merge(
        (array) $bloodDonorUnit,
        [
            'schedule' => array_map(function ($schedule) {
                return [
                    'instances' => $schedule['instansi'] ?? null,
                    'address' => $schedule['alamat'] ?? null,
                    'target' =>  $schedule['jumlah'] ?? null,
                    'coordinate' => [
                        'longitude' => $schedule['lng'] ?? null,
                        'latitude' => $schedule['lat'] ?? null,
                    ],
                ];
            }, $raw['data'] ?? []),
        ]
    );
}


    public function formatContact($raw, $BloodDonorUnitId = null)
    {
        if ($BloodDonorUnitId) {
            $contact = array_filter($raw['data'], function ($contact) use ($BloodDonorUnitId) {
                return (string) $contact['id'] === (string) $BloodDonorUnitId;
            });

            if (!empty($contact)) {
                $contact = array_values($contact)[0];
                return (object) [
                    'id' => (int) $contact['id'],
                    'name' => $contact['nama'] ?? null,
                    'phoneNumber' => $contact['telp'] ?? null,
                    'location' => (object) [
                        'province' => $contact['provinsi'] ?? null,
                        'address' => $contact['alamat'] ?? null,
                        'coordinate' => (object) [
                            'latitude' => $contact['lat'] ?? null,
                            'longitude' => $contact['lng'] ?? null,
                        ],
                    ]
                ];
            }

            return null;
        }

        return array_values(array_filter(array_map(function ($contact) {
            $id = (string) $contact['id']; 
            if (substr($id, 0, 2) === $this->provinceId) {
                return [
                    'id' => (int) $contact['id'],
                    'name' => $contact['nama'] ?? null,
                    'phoneNumber' => $contact['telp'] ?? null,
                    'location' => [
                        'province' => $contact['provinsi'] ?? null,
                        'address' => $contact['alamat'] ?? null,
                        'coordinate' => [
                            'latitude' => $contact['lat'] ?? null,
                            'longitude' => $contact['lng'] ?? null,
                        ],
                    ]
                ];
            }
            return null; 
        }, $raw['data'] ?? [])));
    }




}
