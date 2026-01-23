<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IvaoWhazzupUpdatedEvent implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public array $whazzupData;

	public function __construct($whazzupData)
	{
		$this->whazzupData = $whazzupData;
	}

	public function broadcastOn(): array
	{
		return [
			new Channel('ivao-whazzup-updates')
		];
	}
}
