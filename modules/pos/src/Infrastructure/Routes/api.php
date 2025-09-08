<?php
use Illuminate\Support\Facades\Route;

Route::prefix('api/pos')->group(function () {
    Route::get('ping', fn() => response()->json(['ok' => true, 'module' => 'pos']));
});
