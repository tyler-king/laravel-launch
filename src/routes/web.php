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

Route::get('/link', function () {
	$app = "test";
	$store = "com.XXX.XXX";
	$link =  "test";
	$id = $store; //TODO this is static
	$root[] = sprintf($link, $app);
	// array_push($root,...($this->routeInfo['remaining'] ?? [])); //TODO add deep link back in

	$params = [];
	$paramString = "";
	if (count($params) > 0) {
		$paramString = "?" . http_build_query($params);
	}

	$location_expo = implode("/", $root) . $paramString;
	$location_deep = implode("/", $root) . $paramString;

	return view('launch::link', ["id" => $id, "app" => $app, "location_expo" => $location_expo, "location_deep" => $location_deep]);
});
