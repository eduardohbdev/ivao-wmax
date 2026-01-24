<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Laravel\Socialite\Contracts\User;

class OAuthAuthenticatedEvent
{
	use Dispatchable;

	public function __construct(public User $user)
	{
	}
}
