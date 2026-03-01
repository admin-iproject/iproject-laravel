<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeocoderService
{
    /**
     * Geocode a full address string using Nominatim (OpenStreetMap).
     * Free, no API key required. Rate limit: 1 req/sec.
     *
     * @param  string|null  $addressLine1
     * @param  string|null  $city
     * @param  string|null  $state
     * @param  string|null  $zip
     * @param  string|null  $country
     * @return array|null  ['lat' => float, 'lng' => float] or null on failure
     */
    public static function geocode(
        ?string $addressLine1,
        ?string $city,
        ?string $state,
        ?string $zip       = null,
        ?string $country   = 'US'
    ): ?array {
        // Build query string from whatever parts are available
        $parts = array_filter([
            $addressLine1,
            $city,
            $state,
            $zip,
            $country,
        ]);

        if (empty($parts)) {
            return null;
        }

        $q = implode(', ', $parts);

        try {
            $response = Http::timeout(5)
                ->withHeaders([
                    // Nominatim requires a User-Agent identifying your app
                    'User-Agent' => config('app.name', 'LaravelApp') . '/1.0',
                ])
                ->get('https://nominatim.openstreetmap.org/search', [
                    'q'              => $q,
                    'format'         => 'json',
                    'limit'          => 1,
                    'addressdetails' => 0,
                ]);

            if ($response->successful()) {
                $results = $response->json();
                if (!empty($results[0])) {
                    return [
                        'lat' => (float) $results[0]['lat'],
                        'lng' => (float) $results[0]['lon'],
                    ];
                }
            }
        } catch (\Throwable $e) {
            // Geocoding is best-effort — never block a save
            Log::warning('Geocoder failed: ' . $e->getMessage(), ['query' => $q]);
        }

        return null;
    }

    /**
     * Geocode from a model that has address_line1, city, state, zip fields.
     * Updates the model's lat/lng in place (does not save — caller must save).
     */
    public static function geocodeModel(object $model): bool
    {
        $coords = static::geocode(
            $model->address_line1 ?? null,
            $model->city          ?? null,
            $model->state         ?? null,
            $model->zip           ?? null,
        );

        if ($coords) {
            $model->lat = $coords['lat'];
            $model->lng = $coords['lng'];
            return true;
        }

        return false;
    }
}
