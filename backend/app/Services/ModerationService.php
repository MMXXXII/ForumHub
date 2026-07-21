<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ModerationService
{
    public function check(string $text): array
    {
        if (! config('services.moderation.enabled')) {
            return $this->fallback('disabled');
        }

        try {
            $response = Http::timeout(config('services.moderation.timeout'))
                ->acceptJson()
                ->post(config('services.moderation.url').'/moderate', ['text' => $text]);

            if (! $response->successful()) {
                Log::warning('Moderation service returned error', ['status' => $response->status()]);

                return $this->fallback('service_error');
            }

            $data = $response->json();

            return [
                'status' => $data['status'] === 'rejected' ? 'rejected' : 'approved',
                'score' => (float) ($data['score'] ?? 0),
                'label' => $data['label'] ?? 'none',
            ];
        } catch (\Throwable $e) {
            Log::warning('Moderation service unavailable', ['message' => $e->getMessage()]);

            return $this->fallback('unavailable');
        }
    }

    private function fallback(string $reason): array
    {
        return [
            'status' => 'pending',
            'score' => 0.0,
            'label' => $reason,
        ];
    }
}
