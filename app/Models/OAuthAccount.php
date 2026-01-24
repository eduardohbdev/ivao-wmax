<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OAuthAccount extends Model
{
	protected $table = 'oauth_accounts';

	protected $fillable = [
		'provider',
		'provider_id',
		'access_token',
		'refresh_token',
	];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}
}
