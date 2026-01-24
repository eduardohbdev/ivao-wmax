<?php


namespace App\Actions;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final readonly class IvaoFetchWhazzup
{
	/**
	 * @throws RequestException
	 * @throws ConnectionException
	 */
	public function execute(): array
	{
		$apiVersion = config('services.ivao.api.version');
		$endpoint = config('services.ivao.api.endpoint');

		return Http::timeout(15)
			->retry(2, 500)
			->get("$endpoint/$apiVersion/tracker/whazzup")
			->throw()
			->json();
	}
}
