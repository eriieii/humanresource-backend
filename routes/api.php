<?php

use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\ResponsibilityController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\TeamController;
use App\Http\Controllers\API\UserController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Auth API
Route::name('auth.')->group(function () {
    Route::post('login', [UserController::class, 'login'])->name('login');
    Route::post('register', [UserController::class, 'register'])->name('register');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [UserController::class, 'logout'])->name('logout');
        Route::get('user', [UserController::class, 'fetch'])->name('fetch');
    });
});
//Company
Route::prefix('company')->middleware('auth:sanctum')->name('company.')->group(function () {
    Route::get('', [CompanyController::class, 'fetch'])->name('fetch');
    Route::post('/create', [CompanyController::class, 'create'])->name('create');
    Route::post('/update/{id}', [CompanyController::class, 'update'])->name('update');
});

//Team
Route::prefix('teams')->middleware('auth:sanctum')->name('teams.')->group(function () {
    Route::get('', [TeamController::class, 'fetch'])->name('fetch');
    Route::post('/create', [TeamController::class, 'create'])->name('create');
    Route::post('/update/{id}', [TeamController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [TeamController::class, 'destroy'])->name('destroy');
});

//Role
Route::prefix('role')->middleware('auth:sanctum')->name('role.')->group(function () {
    Route::get('', [RoleController::class, 'fetch'])->name('fetch');
    Route::post('/create', [RoleController::class, 'create'])->name('create');
    Route::post('/update/{id}', [RoleController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [RoleController::class, 'destroy'])->name('destroy');
});

//Responsibility
Route::prefix('responsibility')->middleware('auth:sanctum')->name('responsibility.')->group(function () {
    Route::get('', [ResponsibilityController::class, 'fetch'])->name('fetch');
    Route::post('/create', [ResponsibilityController::class, 'create'])->name('create');   
    Route::delete('/delete/{id}', [ResponsibilityController::class, 'destroy'])->name('destroy');
});


