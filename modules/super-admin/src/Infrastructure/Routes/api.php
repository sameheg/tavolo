<?php
use Illuminate\Support\Facades\Route;
Route::prefix('api/super-admin')->group(function(){
    Route::get('ping', fn() => response()->json(['ok' => true, 'module' => 'super-admin']));
});
