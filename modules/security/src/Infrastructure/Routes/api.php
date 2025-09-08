<?php
use Illuminate\Support\Facades\Route;
Route::prefix('api/security')->group(function(){
    Route::get('ping', fn() => response()->json(['ok' => true, 'module' => 'security']));
});
