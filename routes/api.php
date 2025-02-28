<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ActivityController,
    LoginController,
    RegisterController,
    UserController,
};

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

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

// TODO: ver porque al borrar o actualizar un usuario laravel intenta redirigir a la vista de login y da status 404

// Login and register
Route::post('/register', [RegisterController::class, 'store'])->name('user.store');
Route::post('/login', [LoginController::class, 'login'])->name('user.login');
Route::middleware('auth:api')->prefix('/appActivities')->group(function () {
    // users' routes
    Route::middleware('User.Ownership')->prefix('/users')->group(function () {
        Route::get('/{user}', [UserController::class, 'show'])->name('user.details');
        Route::put('/{user}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('user.delete');

        // users' activities
        Route::post('/{user}/activities/{activity}', [ActivityController::class, 'joinActivity'])->name('user.activity.join');
    });

// activities
    Route::get('/activity/{activity}', [ActivityController::class, 'show'])->name('activity.details');
    Route::get('/export/activities', [ActivityController::class, 'exportActivities'])->name('activities.export');    

    Route::middleware('admin.permissions')->prefix('/activity')->group(function () {
        Route::post('/', [ActivityController::class, 'store'])->name('activity.create');
        Route::put('/{activity}', [ActivityController::class, 'update'])->name('activity.update');
        Route::delete('/{activity}', [ActivityController::class, 'destroy'])->name('activity.delete');
    });

    Route::middleware('admin.permissions')->post('/import/activities', [ActivityController::class, 'importActivities'])->name('activities.import');
});
