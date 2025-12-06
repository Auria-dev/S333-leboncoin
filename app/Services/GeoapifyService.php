<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeoapifyService
{
    protected $baseUrl = 'https://api.geoapify.com/v1/geocode/search';

    /**
     * 
     *
     * @param string
     * @return array|null
     */
    public function geocode(string $address): ?array
    {
        $response = Http::get($this->baseUrl, [
            'text' => $address,
            'apiKey' => config('services.geoapify.key'),
            'limit' => 1
        ]);

        if ($response->failed()) {
            return null;
        }

        $data = $response->json();

        if (empty($data['features'])) {
            return null;
        }

        $coordinates = $data['features'][0]['geometry']['coordinates'];

        return [
            'lon' => $coordinates[0],
            'lat' => $coordinates[1],
            'formatted_address' => $data['features'][0]['properties']['formatted'] ?? $address
        ];
    }
}