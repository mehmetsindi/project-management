<?php

namespace App\Services;

use Carbon\Carbon;

class CarrierService
{
    private array $carriers = [
        'DHL' => [
            'name' => 'DHL Express',
            'base_rate' => 25.00,
            'per_km_rate' => 0.15,
            'avg_days' => 3,
            'reliability' => 95,
        ],
        'UPS' => [
            'name' => 'UPS Worldwide',
            'base_rate' => 22.00,
            'per_km_rate' => 0.12,
            'avg_days' => 4,
            'reliability' => 92,
        ],
        'FedEx' => [
            'name' => 'FedEx International',
            'base_rate' => 28.00,
            'per_km_rate' => 0.18,
            'avg_days' => 2,
            'reliability' => 97,
        ],
        'Aras' => [
            'name' => 'Aras Kargo',
            'base_rate' => 15.00,
            'per_km_rate' => 0.08,
            'avg_days' => 5,
            'reliability' => 88,
        ],
        'Yurtici' => [
            'name' => 'Yurtiçi Kargo',
            'base_rate' => 18.00,
            'per_km_rate' => 0.10,
            'avg_days' => 4,
            'reliability' => 90,
        ],
    ];

    private array $statuses = [
        'pending' => 'Pending Pickup',
        'picked_up' => 'Picked Up',
        'in_transit' => 'In Transit',
        'out_for_delivery' => 'Out for Delivery',
        'delivered' => 'Delivered',
        'exception' => 'Exception',
    ];

    /**
     * Get available carriers with their information
     */
    public function getCarriers(): array
    {
        return $this->carriers;
    }

    /**
     * Calculate shipping rates for all carriers
     */
    public function calculateRates(string $origin, string $destination, float $weight = 1.0): array
    {
        // Mock distance calculation (in reality, would use coordinates)
        $distance = $this->calculateDistance($origin, $destination);

        $rates = [];
        foreach ($this->carriers as $code => $carrier) {
            $cost = $carrier['base_rate'] + ($distance * $carrier['per_km_rate']);
            $cost *= $weight; // Adjust for weight

            $rates[] = [
                'carrier_code' => $code,
                'carrier_name' => $carrier['name'],
                'cost' => round($cost, 2),
                'currency' => 'USD',
                'estimated_days' => $carrier['avg_days'],
                'estimated_delivery' => Carbon::now()->addDays($carrier['avg_days'])->format('Y-m-d'),
                'reliability' => $carrier['reliability'],
            ];
        }

        // Sort by cost
        usort($rates, fn($a, $b) => $a['cost'] <=> $b['cost']);

        return $rates;
    }

    /**
     * Create a shipment and generate tracking number
     */
    public function createShipment(string $carrierCode, string $origin, string $destination): array
    {
        if (!isset($this->carriers[$carrierCode])) {
            throw new \InvalidArgumentException("Invalid carrier code: {$carrierCode}");
        }

        $carrier = $this->carriers[$carrierCode];
        $trackingNumber = $this->generateTrackingNumber($carrierCode);

        return [
            'tracking_number' => $trackingNumber,
            'carrier' => $carrierCode,
            'carrier_name' => $carrier['name'],
            'status' => 'pending',
            'status_label' => $this->statuses['pending'],
            'estimated_delivery' => Carbon::now()->addDays($carrier['avg_days'])->format('Y-m-d'),
            'created_at' => Carbon::now()->toIso8601String(),
        ];
    }

    /**
     * Get tracking information for a shipment
     */
    public function trackShipment(string $trackingNumber): array
    {
        // Mock tracking data
        $createdAt = Carbon::now()->subDays(rand(1, 5));
        $currentStatus = $this->getMockStatus($createdAt);

        return [
            'tracking_number' => $trackingNumber,
            'status' => $currentStatus,
            'status_label' => $this->statuses[$currentStatus],
            'events' => $this->generateTrackingEvents($createdAt, $currentStatus),
            'estimated_delivery' => $createdAt->copy()->addDays(rand(3, 7))->format('Y-m-d'),
            'last_updated' => Carbon::now()->toIso8601String(),
        ];
    }

    /**
     * Update shipment status
     */
    public function updateShipmentStatus(string $trackingNumber, string $newStatus): array
    {
        if (!isset($this->statuses[$newStatus])) {
            throw new \InvalidArgumentException("Invalid status: {$newStatus}");
        }

        return [
            'tracking_number' => $trackingNumber,
            'status' => $newStatus,
            'status_label' => $this->statuses[$newStatus],
            'updated_at' => Carbon::now()->toIso8601String(),
        ];
    }

    /**
     * Calculate mock distance between two locations (in km)
     */
    private function calculateDistance(string $origin, string $destination): float
    {
        // Mock calculation based on string similarity
        // In reality, would use coordinates and haversine formula
        $hash = crc32($origin . $destination);
        return abs($hash % 1000) + 50; // Random distance between 50-1050 km
    }

    /**
     * Generate a realistic tracking number
     */
    private function generateTrackingNumber(string $carrierCode): string
    {
        $prefix = strtoupper(substr($carrierCode, 0, 3));
        $number = str_pad(rand(1000000000, 9999999999), 10, '0', STR_PAD_LEFT);
        return $prefix . $number;
    }

    /**
     * Get mock status based on shipment age
     */
    private function getMockStatus(Carbon $createdAt): string
    {
        $daysOld = $createdAt->diffInDays(Carbon::now());

        if ($daysOld === 0)
            return 'pending';
        if ($daysOld === 1)
            return 'picked_up';
        if ($daysOld <= 3)
            return 'in_transit';
        if ($daysOld === 4)
            return 'out_for_delivery';
        return 'delivered';
    }

    /**
     * Generate mock tracking events
     */
    private function generateTrackingEvents(Carbon $createdAt, string $currentStatus): array
    {
        $events = [];
        $statusOrder = ['pending', 'picked_up', 'in_transit', 'out_for_delivery', 'delivered'];
        $currentIndex = array_search($currentStatus, $statusOrder);

        $locations = [
            'Istanbul Distribution Center',
            'Ankara Sorting Facility',
            'Izmir Hub',
            'Regional Warehouse',
            'Local Delivery Station',
        ];

        for ($i = 0; $i <= $currentIndex; $i++) {
            $status = $statusOrder[$i];
            $timestamp = $createdAt->copy()->addDays($i);

            $events[] = [
                'status' => $status,
                'status_label' => $this->statuses[$status],
                'location' => $locations[$i] ?? 'In Transit',
                'timestamp' => $timestamp->toIso8601String(),
                'description' => $this->getEventDescription($status),
            ];
        }

        return array_reverse($events); // Most recent first
    }

    /**
     * Get description for tracking event
     */
    private function getEventDescription(string $status): string
    {
        return match ($status) {
            'pending' => 'Shipment information received',
            'picked_up' => 'Package picked up by carrier',
            'in_transit' => 'Package in transit to destination',
            'out_for_delivery' => 'Out for delivery',
            'delivered' => 'Package delivered successfully',
            'exception' => 'Delivery exception occurred',
            default => 'Status updated',
        };
    }

    /**
     * Get available status options
     */
    public function getStatuses(): array
    {
        return $this->statuses;
    }
}
