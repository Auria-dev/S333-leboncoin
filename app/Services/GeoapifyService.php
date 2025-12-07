<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeoapifyService
{
    protected $baseUrl = 'https://nominatim.openstreetmap.org/search';

    /**
     * Geocode an address using Nominatim (OSM)
     * * @param string $address
     * @return array|null
     */
    public function geocode(string $address): ?array
    {
        $response = Http::withHeaders([
            'User-Agent' => config('app.name') . ' (mailto:' . config('mail.from.address') . ')'
        ])->get($this->baseUrl, [
            'q' => $address,
            'format' => 'json',
            'limit' => 1,
            'addressdetails' => 1
        ]);

        if ($response->failed()) {
            \Log::error('Nominatim API request failed: ' . $response->body());
            return null;
        }

        $data = $response->json();

        if (empty($data)) {
            return null;
        }

        $firstResult = $data[0];

        return [
            'lon' => (float) $firstResult['lon'],
            'lat' => (float) $firstResult['lat'],
            'formatted_address' => $firstResult['display_name'] ?? $address
        ];
    }
}