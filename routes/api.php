<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/products', [ProductController::class, 'index'])->name('products.index');

Route::get('/test', function() {
    \Log::info('Test route works');
    return response()->json(['test' => true]);
});

Route::any('/{any}', function($any) {
    \Log::info('Catch-all route reached: ' . $any);
    return response()->json(['path' => $any, 'params' => request()->all()]);
})->where('any', '.*');