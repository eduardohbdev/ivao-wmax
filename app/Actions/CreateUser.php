<?php


namespace App\Actions;

use App\Models\User;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

final readonly class CreateUser
{
	public function execute(array $input): User
	{
		return User::create($input);
	}
}
