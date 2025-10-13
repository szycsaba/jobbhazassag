<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PartnerInviteService
{

    public function send(string $email, string $invitersName, string $name): array
    {
        $url = config('services.make.partner_invite_url');
        if (!$url) return [false, 'Webhook URL hiányzik.', 0];

        try {
            $resp = Http::timeout(10)
                ->acceptJson()->asJson()
                ->post($url, [
                    'email'         => $email,
                    'inviters_name' => $invitersName,
                    'name'          => $name,
                ]);

            Log::channel('single')->info('Make invite', [
                'status' => $resp->status(),
                'body'   => substr($resp->body(), 0, 500),
            ]);

            if ($resp->successful()) {
                return [true, 'A meghívót sikeresen továbbítottuk!', $resp->status()];
            }
            return [false, "Nem sikerült továbbítani (HTTP {$resp->status()}).", $resp->status()];
        } catch (\Throwable $e) {
            Log::error('Make invite error', ['err' => $e->getMessage()]);
            return [false, 'Hálózati hiba.', 0];
        }
    }
}
