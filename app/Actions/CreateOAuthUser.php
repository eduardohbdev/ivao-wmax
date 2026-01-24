<?php


namespace App\Actions;

use App\Models\OAuthAccount;
use Laravel\Socialite\Contracts\User;

final readonly class CreateOAuthUser
{
	public function execute(User $input): OAuthAccount
	{
		return OAuthAccount::create([
			'provider' => 'ivao',
			'provider_id' => $input->id,
			'access_token' => $input->token,
			'refresh_token' => $input->refreshToken
		]);
	}
}
