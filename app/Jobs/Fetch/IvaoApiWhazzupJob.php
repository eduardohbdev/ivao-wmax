<?php

namespace App\Jobs\Fetch;

use App\Events\IvaoWhazzupUpdatedEvent;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Log;

class IvaoApiWhazzupJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public function handle(): void
	{
		try {
			$apiVersion = config('services.ivao.api.version');
			$endpoint = config('services.ivao.api.endpoint');
			$cacheKey = config('services.ivao.cache.key');
			$ttl = config('services.ivao.cache.ttl');

			$response = Http::timeout(15)
				->retry(2, 500)
				->get("{$endpoint}/{$apiVersion}/tracker/whazzup");

			if ($response->successful()) {
				Cache::put($cacheKey, $response->json(), now()->addSeconds($ttl));
				IvaoWhazzupUpdatedEvent::dispatch($response->json());
			}

		} catch (Exception $e) {
			Log::error("Falha ao buscar dados IVAO: " . $e->getMessage());
		}
	}
}
