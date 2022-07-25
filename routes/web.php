<?php

use App\Http\Controllers\DomainPingBatchesController;
use App\Http\Controllers\EndPointsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', fn() => redirect(route('end-points.index')));

Route::resource('end-points', EndPointsController::class);
Route::resource('domain-pings-batches', DomainPingBatchesController::class);
Route::get('domain-stats', [DomainPingBatchesController::class, 'domain_stat'])->name('domain.stats');


require __DIR__.'/auth.php';
