<?php

namespace App\Listeners;

use App\Actions\CreateOAuthUser;
use App\Actions\CreateUser;
use App\Events\OAuthAuthenticatedEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Throwable;

class CreateUserListener
{
	public bool $afterCommit = true;

	public function __construct(public CreateUser $actionUser, public CreateOAuthUser $actionOAuthUser)
	{
	}

	/**
	 * @throws Throwable
	 */
	public function handle(OAuthAuthenticatedEvent $event): void
	{
		DB::transaction(function () use ($event) {
			$user = $this->actionUser->execute([
				'name' => $event->user->nickname,
				'email' => $event->user->email,
				'password' => Hash::make(Str::random(16))
			]);

			$oauthAccount = $this->actionOAuthUser->execute($event->user);

			DB::afterCommit(static function () use ($user, $oauthAccount) {
				$oauthAccount->user()->associate($user);
				$oauthAccount->save();
			});
		});
	}
}
