<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class IvaoWhazzupController extends Controller
{
	public function index(): Response
	{
		$dataCache = Cache::get(config('services.ivao.cache.key'), []);
		return Inertia::render('webeye', ['whazzupData' => $dataCache]);
	}
}
