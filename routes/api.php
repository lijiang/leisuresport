<?php

use App\Http\Controllers\Api\Sports\V1\SportV1Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Sports API Route

Route::group(['prefix' => '/sports/v1.0', 'middleware' => ['throttle:120,1', 'cors', 'json'], 'as' => 'api.',], function () {
    Route::get('/statistic/{year}', [SportV1Controller::class, 'yearlyHeadcountStatistics'])->name('index');
    Route::get('/{sport}/statistic/{year}', [SportV1Controller::class, 'yearlyDetailHeadcountStatistics'])->name('detail');

});
