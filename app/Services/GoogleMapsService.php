<?php

namespace App\Services;

class GoogleMapsService
{
    /**
     * Mock geocoding service that returns fake coordinates
     * In production, this would call the actual Google Maps Geocoding API
     */
    public function geocode(string $address): ?array
    {
        // Mock data - in production, this would make an API call
        $mockLocations = [
            'istanbul' => ['lat' => 41.0082, 'lng' => 28.9784],
            'ankara' => ['lat' => 39.9334, 'lng' => 32.8597],
            'izmir' => ['lat' => 38.4237, 'lng' => 27.1428],
            'bursa' => ['lat' => 40.1826, 'lng' => 29.0665],
            'antalya' => ['lat' => 36.8969, 'lng' => 30.7133],
        ];

        // Simple matching - check if any city name is in the address
        $addressLower = strtolower($address);
        foreach ($mockLocations as $city => $coords) {
            if (str_contains($addressLower, $city)) {
                return [
                    'lat' => $coords['lat'],
                    'lng' => $coords['lng'],
                    'formatted_address' => ucfirst($city) . ', Turkey',
                ];
            }
        }

        // Default to Istanbul if no match
        return [
            'lat' => 41.0082,
            'lng' => 28.9784,
            'formatted_address' => $address,
        ];
    }
}
