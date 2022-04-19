<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\V1\Auth\AuthController;
use App\Http\Controllers\V1\HospitalController;
use App\Http\Controllers\V1\PeopleController;
use App\Http\Controllers\V1\KeyWordController;
use App\Http\Controllers\V1\Auth\GoogleController;
use App\Http\Controllers\V1\Auth\ConfirmPasswordController;
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
Route::group(['prefix' => 'v1', 'as' => 'v1.', 'namespace' => 'V1'], function () {

    Route::group(['middleware' => ['cors', 'json.response']], function () {

        // AUTH APIs
        Route::post('/login', [AuthController::class, 'login'])->name('login.api');
        Route::post('/signup', [AuthController::class, 'signup'])->name('signup.api');
        Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle']);
        Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

        Route::post('/register', [AuthController::class, 'register'])->name('register.api');
        Route::post('/confirm-code', [AuthController::class, 'confirmCode'])->name('confirm.code.api');
    });

    Route::group(['middleware' => ['auth:sanctum']], function () {

        // CHANGE PASSWORD
        Route::post('/change-password', [ConfirmPasswordController::class, 'index'])->name('change.password.api');

        // HOSPITAL APIs
        Route::get('/hospitals', [HospitalController::class, 'index'])->name('hospitals.api');
        Route::post('/hospitals', [HospitalController::class, 'store'])->name('hospitals.store.api');
        Route::get('/hospitals/{id}', [HospitalController::class, 'detail'])->name('hospitals.detail.api');
        Route::put('/hospitals/{id}', [HospitalController::class, 'update'])->name('hospitals.update.api');
        Route::delete('/hospitals/{id}', [HospitalController::class, 'delete'])->name('hospitals.delete.api');

        // DOCTOR APIs
        Route::get('/doctors', [PeopleController::class, 'index'])->name('doctors.api');
        Route::post('/doctors', [PeopleController::class, 'store'])->name('doctors.store.api');
        Route::get('/doctors/{id}', [PeopleController::class, 'detail'])->name('doctors.detail.api');
        Route::put('/doctors/{id}', [PeopleController::class, 'update'])->name('doctors.update.api');
        Route::delete('/doctors/{id}', [PeopleController::class, 'delete'])->name('doctors.delete.api');

        // FAMILY APIs
        Route::get('/family', [PeopleController::class, 'index'])->name('family.api');
        Route::post('/family', [PeopleController::class, 'store'])->name('family.store.api');
        Route::get('/family/{id}', [PeopleController::class, 'getHospitalDetail'])->name('family.detail.api');
        Route::put('/family/{id}', [PeopleController::class, 'update'])->name('family.update.api');
        Route::delete('/family/{id}', [PeopleController::class, 'delete'])->name('family.delete.api');

        // MEDICINE APIs
        Route::get('/medicines', [KeyWordController::class, 'index'])->name('medicines.api');
        Route::post('/medicines', [KeyWordController::class, 'store'])->name('medicines.store.api');
        Route::get('/medicines/{id}', [KeyWordController::class, 'detail'])->name('medicines.detail.api');
        Route::put('/medicines/{id}', [KeyWordController::class, 'update'])->name('medicines.update.api');
        Route::delete('/medicines/{id}', [KeyWordController::class, 'delete'])->name('medicines.delete.api');
        
    });

    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });
});