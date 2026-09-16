<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SpatialCalculationService
{
    /**
     * Hitung Luas Poligon (dalam km²)
     */
    public static function calculatePolygonAreaKm2(array $coordinates): float
    {
        if (count($coordinates) < 3) {
            return 0.0;
        }

        $earthRadiusMeters = 6378137;
        $totalArea = 0.0;
        $length = count($coordinates);

        for ($i = 0; $i < $length; $i++) {
            $p1 = $coordinates[$i];
            $p2 = $coordinates[($i + 1) % $length];

            $lat1 = deg2rad($p1[0]);
            $lng1 = deg2rad($p1[1]);
            $lat2 = deg2rad($p2[0]);
            $lng2 = deg2rad($p2[1]);

            $totalArea += ($lng2 - $lng1) * (2 + sin($lat1) + sin($lat2));
        }

        $totalArea = abs($totalArea * ($earthRadiusMeters * $earthRadiusMeters) / 2.0);
        return round($totalArea / 1000000.0, 2);
    }

    /**
     * Tembak Overpass API OpenStreetMap untuk Menghitung Bangunan Nyata dalam Poligon
     */
    public static function fetchRealDataFromOSM(array $coordinates): array
    {
        $areaKm2 = self::calculatePolygonAreaKm2($coordinates);

        if ($areaKm2 <= 0 || count($coordinates) < 3) {
            return [
                'luas_area_km2'            => 0,
                'total_jiwa_terdampak'     => 0,
                'total_kk_terdampak'       => 0,
                'total_bangunan_terdampak' => 0,
                'estimasi_pengungsi_awal'  => 0,
            ];
        }

        try {
            // Format string koordinat untuk Overpass API: "lat1 lng1 lat2 lng2 lat3 lng3 ..."
            $polyCoords = [];
            foreach ($coordinates as $coord) {
                $polyCoords[] = "{$coord[0]} {$coord[1]}";
            }
            $polyString = implode(' ', $polyCoords);

            // Query Overpass QL untuk menghitung elemen 'building' di dalam poly
            $query = '[out:json][timeout:15];' .
                     'way["building"](poly:"' . $polyString . '");' .
                     'out count;';

            $response = Http::asForm()->timeout(10)->post('https://overpass-api.de/api/interpreter', [
                'data' => $query
            ]);

            $totalBangunan = 0;

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['elements'][0]['tags']['total'])) {
                    $totalBangunan = (int) $data['elements'][0]['tags']['total'];
                }
            }

            // Jika daerah pemukiman belum terpetakan sempurna di OSM, berikan batas aman fallback
            if ($totalBangunan === 0 && $areaKm2 > 0.01) {
                // Asumsi dasar jika query kosong tapi di daratan
                $totalBangunan = (int) round($areaKm2 * 350); 
            }

            // Rasio Demografi BPS: Rata-rata 3.5 jiwa/rumah & 4 jiwa/KK
            $totalJiwa = (int) round($totalBangunan * 3.5);
            $totalKK   = (int) round($totalJiwa / 4);

            return [
                'luas_area_km2'            => $areaKm2,
                'total_jiwa_terdampak'     => $totalJiwa,
                'total_kk_terdampak'       => $totalKK,
                'total_bangunan_terdampak' => $totalBangunan,
                'estimasi_pengungsi_awal'  => (int) round($totalJiwa * 0.35),
            ];

        } catch (\Exception $e) {
            Log::error('Overpass API Error: ' . $e->getMessage());

            // Fallback kalkulasi jika API timeout/error
            $totalJiwa = (int) round($areaKm2 * 800);
            return [
                'luas_area_km2'            => $areaKm2,
                'total_jiwa_terdampak'     => $totalJiwa,
                'total_kk_terdampak'       => (int) round($totalJiwa / 4),
                'total_bangunan_terdampak' => (int) round($totalJiwa / 3.5),
                'estimasi_pengungsi_awal'  => (int) round($totalJiwa * 0.35),
            ];
        }
    }
}