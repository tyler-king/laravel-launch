<?php

use Illuminate\Support\Facades\Route;

Route::get('/terms', function () {
	return view('launch::terms');
});

Route::get('/privacy', function () {
	return view('launch::privacy');
});

Route::get('/docs', function () {
	return view('launch::docs');
});
