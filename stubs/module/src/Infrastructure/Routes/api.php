<?php
use Illuminate\Support\Facades\Route;

Route::prefix('api/{{module}}')->group(function () {
    Route::get('ping', fn() => response()->json(['ok' => true, 'module' => '{{module}}']));
});
