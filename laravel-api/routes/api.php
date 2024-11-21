<?php

use App\Http\Controllers\API\EmailChangeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\GetHobbiesController;

use App\Models\User;
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

Route::get('/hobbies', GetHobbiesController::class)->name('hobbies');

Route::middleware(['auth:sanctum','verified'])->group(function () {

    // User 
    Route::get('/user', [UserController::class, 'getUser'])->name('user');
    Route::get("/fetch-users", [UserController::class, 'fetchUsers'])->name('fetch-users');
    Route::post("/set-username/{id}", [UserController::class, 'updateUsername'])->name('set-name');

    // Email Change
    Route::post("/request-email-change", [EmailChangeController::class, 'requestEmailChange'])->name('request-email-change');
    Route::post("/change-email-confirmed", [EmailChangeController::class, 'updateEmailConfirmed'])->name('change-email-confirmed');

    
    // Profile
    Route::get('/profile/{id}', [ProfileController::class, 'getProfile'])->name('get-user-profile');
    Route::post('/profile/{id}', [ProfileController::class, 'update'])->name('update-user-profile');
    
});

require __DIR__.'/auth.php';
