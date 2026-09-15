<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GpsTraceService
{
    protected string $baseUrl;
    protected ?string $accessToken;
    protected string $defaultAppId;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.gpstrace.base_url', 'https://api.gps-trace.com'), '/');
        $this->accessToken = config('services.gpstrace.access_token');
        $this->defaultAppId = config('services.gpstrace.default_app_id', 'b901da51-ce00-4af2-b978-8d0fca8ae1ea');
    }

    /**
     * Build HTTP client with Access Token header
     */
    protected function client()
    {
        return Http::withHeaders([
            'X-AccessToken' => $this->accessToken ?? '',
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Register a new physical E-Bike tracker unit in GPS-Trace platform
     * POST /provider/units
     */
    public function registerUnit(string $unitName, string $ident, int $hwId, ?int $accountId = null, ?string $appId = null): ?array
    {
        if (!$this->accessToken) {
            Log::warning('GPS-Trace Access Token is missing. Unit registration skipped.');
            return null;
        }

        $payload = [
            'name' => $unitName,
            'ident' => $ident,
            'hw_id' => $hwId,
            'app_id' => $appId ?? $this->defaultAppId,
        ];

        if ($accountId) {
            $payload['account_id'] = $accountId;
        }

        $response = $this->client()->post("{$this->baseUrl}/provider/units", $payload);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('GPS-Trace Register Unit Failed', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return null;
    }

    /**
     * Get live telemetry and unit info by GPS Unit ID
     * GET /provider/units/{id}
     */
    public function getUnitDetails(string $gpsUnitId): ?array
    {
        if (!$this->accessToken) {
            return null;
        }

        $response = $this->client()->get("{$this->baseUrl}/provider/units/{$gpsUnitId}");

        if ($response->successful()) {
            return $response->json();
        }

        Log::error("GPS-Trace Get Unit Details Failed for ID: {$gpsUnitId}", [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return null;
    }

    /**
     * Retrieve supported hardware device types
     * GET /platform/hardware/devices
     */
    public function getHardwareDevices(): array
    {
        $response = Http::get("{$this->baseUrl}/platform/hardware/devices");

        return $response->successful() ? ($response->json() ?? []) : [];
    }

    /**
     * Retrieve hardware manufacturers
     * GET /platform/hardware/manufacturers
     */
    public function getHardwareManufacturers(): array
    {
        $response = Http::get("{$this->baseUrl}/platform/hardware/manufacturers");

        return $response->successful() ? ($response->json() ?? []) : [];
    }

    /**
     * Generate SSO Application Token for client portal access
     * GET /provider/users/{id}/appToken/{app_id}
     */
    public function getAppToken(string $userId, ?string $appId = null, string $tokenType = 'service'): ?array
    {
        if (!$this->accessToken) {
            return null;
        }

        $targetAppId = $appId ?? $this->defaultAppId;
        $response = $this->client()->get("{$this->baseUrl}/provider/users/{$userId}/appToken/{$targetAppId}?token_type={$tokenType}");

        return $response->successful() ? $response->json() : null;
    }
}
