<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('oauth_accounts', function (Blueprint $table) {
			$table->id();
			$table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
			$table->string('provider');
			$table->string('provider_id');
			$table->text('access_token');
			$table->text('refresh_token');
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('oauth_accounts');
	}
};
