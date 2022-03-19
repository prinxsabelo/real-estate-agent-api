<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['web']], function () {
    Route::get('/login/{provider}', [AuthController::class,'redirectToProvider']);
    Route::get('/login/{provider}/callback',[AuthController::class,'handleCallbackProvider']);
    // Route::get('/logout',[LogoutController::class,'logout']);
    
});


Route::post('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
Route::post('/save-agency',[AuthController::class, 'saveAgency'])->middleware('auth:sanctum');
Route::post('/upload-agency-logo',[AuthController::class, 'uploadAgencyLogo'])->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
