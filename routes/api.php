<?php

use App\Http\Controllers\AssignmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProspectController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::get('/checkUser/{email}', 'checkUser');
    Route::get('/login', 'login_route')->name('login');
    Route::post('/login', 'login');
});

Route::fallback(function () {
    return response([
        'message' => 'Route does not exist'
    ], 404);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(ProspectController::class)->group(function () {
        Route::get('/prospects', 'index');
        Route::post('/prospects', 'store');
        Route::get('/prospects/{prospect}', 'show')
            ->missing(function (Request $request) {
                return missing('prospect', $request->prospect);
            });
        Route::put('/prospects/{prospect}', 'update')
            ->missing(function (Request $request) {
                return missing('prospect', $request->prospect);
            });
        Route::delete('/prospects/{prospect}', 'destroy')
            ->missing(function (Request $request) {
                return missing('prospect', $request->prospect);
            });
    });
    Route::controller(AssignmentController::class)->group(function () {
        Route::get('/assignments', 'index');
        Route::post('/assignments', 'store');
        Route::get('/assignments/{assignment}', 'show')
            ->missing(function (Request $request) {
                return missing('assignment', $request->assignment);
            });
        Route::put('/assignments/{assignment}', 'update')
            ->missing(function (Request $request) {
                return missing('assignment', $request->assignment);
            });
        Route::delete('/assignments/{assignment}', 'destroy')
            ->missing(function (Request $request) {
                return missing('assignment', $request->assignment);
            });
    });
});

function missing($name, $id)
{
    return response([
        'message' => "$name with id: $id does not exist"
    ], 404);
}
