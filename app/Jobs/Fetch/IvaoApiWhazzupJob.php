<?php

namespace App\Jobs\Fetch;

use App\Actions\IvaoFetchWhazzup;
use App\Events\IvaoWhazzupUpdatedEvent;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Log;

class IvaoApiWhazzupJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public function handle(IvaoFetchWhazzup $action): void
	{
		try {

			$dataAction = $action->execute();
			Cache::put(config('services.ivao.cache.key'), $dataAction, now()->addHour());
			IvaoWhazzupUpdatedEvent::dispatch($dataAction);

		} catch (Exception $e) {
			Log::error("Failed to retrieve IVAO data: " . $e->getMessage());
		}
	}
}
