<?php

namespace App\Http\Controllers;

use App\Events\OAuthAuthenticatedEvent;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Log;

class IvaoOAuthController extends Controller
{
	public function redirect(): RedirectResponse
	{
		return Socialite::driver('ivao')->stateless()->scopes(['profile'])->redirect();
	}

	/**
	 * @throws Exception
	 */
	public function callback(): RedirectResponse
	{
		$socialiteUser = Socialite::driver('ivao')->stateless()->user();

		try {
			if ($socialiteUser) {
				OAuthAuthenticatedEvent::dispatch($socialiteUser);
				$user = User::where('email', $socialiteUser->email)->first();
				Auth::login($user);
				return redirect()->intended(route('dashboard'));
			}
		} catch (Exception $e) {
			Log::error($e->getMessage());
		}

		return redirect()->route('login')->withErrors('Unable to authenticate with IVAO.');
	}
}
