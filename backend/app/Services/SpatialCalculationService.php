<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SpatialCalculationService
{
    private const OVERPASS_ENDPOINTS = [
        'https://overpass-api.de/api/interpreter',
        'https://overpass.kumi.systems/api/interpreter',
        'https://overpass.private.coffee/api/interpreter',
    ];

    private const OVERPASS_QUERY_TIMEOUT = 10;
    private const HTTP_TIMEOUT = 12;
    private const MAX_POLYGON_POINTS = 60;

    private const JIWA_PER_BANGUNAN   = 3.5;
    private const JIWA_PER_KK         = 4;
    private const RASIO_PENGUNGSI     = 0.35;

    private const FALLBACK_BANGUNAN_PER_KM2 = 380;

    public static function calculatePolygonAreaKm2(array $coordinates): float
    {
        $ring = self::normalizeRing($coordinates);

        if (count($ring) < 3) {
            return 0.0;
        }

        $earthRadiusMeters = 6378137;
        $totalArea = 0.0;
        $length = count($ring);

        for ($i = 0; $i < $length; $i++) {
            $p1 = $ring[$i];
            $p2 = $ring[($i + 1) % $length];

            $lng1 = deg2rad($p1[0]);
            $lat1 = deg2rad($p1[1]);
            $lng2 = deg2rad($p2[0]);
            $lat2 = deg2rad($p2[1]);

            $totalArea += ($lng2 - $lng1) * (2 + sin($lat1) + sin($lat2));
        }

        $totalArea = abs($totalArea * ($earthRadiusMeters * $earthRadiusMeters) / 2.0);
        return round($totalArea / 1000000.0, 2);
    }

    public static function fetchRealDataFromOSM(array $coordinates): array
    {
        $ring = self::normalizeRing($coordinates);
        $areaKm2 = self::calculatePolygonAreaKm2($ring);

        if (count($ring) < 3 || $areaKm2 <= 0) {
            return self::buildResult(0.0, 0, 'invalid', 'Poligon tidak valid.');
        }

        // Cek Rata-Rata Lintang (Latitude ada di index 1)
        $totalLat = 0;
        foreach ($ring as $pt) {
            $totalLat += $pt[1];
        }
        $avgLat = $totalLat / count($ring);

        // Jika Murni di Laut Lepas Selatan Jawa (Lat < -8.02), PAKSA MURNI 0
        $isSea = $avgLat < -8.02;

        if ($isSea) {
            return self::buildResult($areaKm2, 0, 'osm_sea', 'Wilayah lautan lepas: 0 bangunan.');
        }

        // Cek Cache
        $cacheKey = 'osm_building_count:' . md5(json_encode(self::roundRing($ring)));
        $cached = Cache::get($cacheKey);
        if ($cached !== null) {
            return self::buildResult($areaKm2, (int) $cached, 'osm_cache', 'Data bangunan riil (cache).');
        }

        $totalBangunan = self::queryOverpassBuildingCount($ring);

        // Jika Overpass API gagal/timeout di DARATAN, baru gunakan estimasi daratan
        if ($totalBangunan === null) {
            $estimasi = (int) round($areaKm2 * self::FALLBACK_BANGUNAN_PER_KM2);
            return self::buildResult($areaKm2, $estimasi, 'estimasi', 'Estimasi daratan (Overpass API timeout).');
        }

        Cache::put($cacheKey, $totalBangunan, now()->addHours(12));

        return self::buildResult($areaKm2, $totalBangunan, 'osm', 'Data bangunan riil OpenStreetMap.');
    }

    private static function queryOverpassBuildingCount(array $ring): ?int
    {
        $simplified = self::simplifyRing($ring, self::MAX_POLYGON_POINTS);

        $polyCoords = [];
        foreach ($simplified as $coord) {
            $polyCoords[] = round($coord[1], 6) . ' ' . round($coord[0], 6);
        }
        $polyString = implode(' ', $polyCoords);

        $query = '[out:json][timeout:' . self::OVERPASS_QUERY_TIMEOUT . '];'
            . '('
            . 'way["building"](poly:"' . $polyString . '");'
            . 'relation["building"](poly:"' . $polyString . '");'
            . ');'
            . 'out count;';

        foreach (self::OVERPASS_ENDPOINTS as $endpoint) {
            try {
                // Tambahkan withoutVerifying() & User-Agent Valid untuk Localhost
                $response = Http::withoutVerifying()
                    ->asForm()
                    ->connectTimeout(5)
                    ->timeout(self::HTTP_TIMEOUT)
                    ->withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) RescueLog/1.0',
                        'Accept'     => 'application/json',
                    ])
                    ->post($endpoint, ['data' => $query]);

                if (!$response->successful()) {
                    continue;
                }

                $data = $response->json();

                if (isset($data['elements'][0]['tags']['total'])) {
                    return (int) $data['elements'][0]['tags']['total'];
                }
            } catch (\Throwable $e) {
                Log::warning('Overpass error: ' . $e->getMessage());
                continue;
            }
        }

        return null;
    }

    private static function buildResult(float $areaKm2, int $totalBangunan, string $sumber, string $keterangan): array
    {
        $totalJiwa = (int) round($totalBangunan * self::JIWA_PER_BANGUNAN);
        $totalKK   = $totalJiwa > 0 ? (int) ceil($totalJiwa / self::JIWA_PER_KK) : 0;

        return [
            'luas_area_km2'            => $areaKm2,
            'total_jiwa_terdampak'     => $totalJiwa,
            'total_kk_terdampak'       => $totalKK,
            'total_bangunan_terdampak' => $totalBangunan,
            'estimasi_pengungsi_awal'  => (int) round($totalJiwa * self::RASIO_PENGUNGSI),
            'sumber_data'              => $sumber,
            'keterangan'               => $keterangan,
        ];
    }

    private static function normalizeRing(array $coordinates): array
    {
        $ring = [];
        foreach ($coordinates as $coord) {
            if (!is_array($coord) || count($coord) < 2) continue;
            $point = [(float) $coord[0], (float) $coord[1]];
            $last = end($ring);
            if ($last !== false && $last[0] === $point[0] && $last[1] === $point[1]) continue;
            $ring[] = $point;
        }
        $count = count($ring);
        if ($count > 1 && $ring[0][0] === $ring[$count - 1][0] && $ring[0][1] === $ring[$count - 1][1]) {
            array_pop($ring);
        }
        return array_values($ring);
    }

    private static function roundRing(array $ring): array
    {
        return array_map(fn ($c) => [round($c[0], 6), round($c[1], 6)], $ring);
    }

    private static function simplifyRing(array $ring, int $maxPoints): array
    {
        if (count($ring) <= $maxPoints) return $ring;
        $tolerance = 0.00005;
        for ($i = 0; $i < 25; $i++) {
            $simplified = self::douglasPeucker($ring, $tolerance);
            if (count($simplified) <= $maxPoints && count($simplified) >= 3) return $simplified;
            $tolerance *= 2;
        }
        $step = (int) ceil(count($ring) / $maxPoints);
        $sampled = [];
        foreach ($ring as $idx => $point) {
            if ($idx % $step === 0) $sampled[] = $point;
        }
        return count($sampled) >= 3 ? $sampled : $ring;
    }

    private static function douglasPeucker(array $points, float $tolerance): array
    {
        $count = count($points);
        if ($count < 3) return $points;
        $maxDistance = 0.0;
        $index = 0;
        for ($i = 1; $i < $count - 1; $i++) {
            $distance = self::perpendicularDistance($points[$i], $points[0], $points[$count - 1]);
            if ($distance > $maxDistance) {
                $maxDistance = $distance;
                $index = $i;
            }
        }
        if ($maxDistance > $tolerance) {
            $left  = self::douglasPeucker(array_slice($points, 0, $index + 1), $tolerance);
            $right = self::douglasPeucker(array_slice($points, $index), $tolerance);
            return array_merge(array_slice($left, 0, -1), $right);
        }
        return [$points[0], $points[$count - 1]];
    }

    private static function perpendicularDistance(array $point, array $lineStart, array $lineEnd): float
    {
        $dx = $lineEnd[0] - $lineStart[0];
        $dy = $lineEnd[1] - $lineStart[1];
        if ($dx === 0.0 && $dy === 0.0) {
            return sqrt(pow($point[0] - $lineStart[0], 2) + pow($point[1] - $lineStart[1], 2));
        }
        $numerator = abs($dy * $point[0] - $dx * $point[1] + $lineEnd[0] * $lineStart[1] - $lineEnd[1] * $lineStart[0]);
        $denominator = sqrt($dx * $dx + $dy * $dy);
        return $numerator / $denominator;
    }
}