<?php
use Illuminate\Support\Facades\Route;
Route::prefix('api/core')->group(function(){
    Route::get('ping', fn() => response()->json(['ok' => true, 'module' => 'core']));
});
