<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/db-test', function () {
    try {
        \DB::connection()->getPdo();
        return 'Database connected successfully.';
    } catch (\Exception $e) {
        return 'Failed to connect to the database: ' . $e->getMessage();
    }
});
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});
