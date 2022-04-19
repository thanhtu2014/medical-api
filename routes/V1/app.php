<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\V1\Auth\AuthController;
use App\Http\Controllers\V1\HospitalController;
use App\Http\Controllers\V1\PeopleController;
use App\Http\Controllers\V1\FolderController;

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

    });

    Route::group(['middleware' => ['auth:sanctum']], function () {

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

        // FOLDER APIs
        Route::get('/folders', [FolderController::class, 'index'])->name('folders.api');
        Route::post('/folders', [FolderController::class, 'store'])->name('folders.store.api');
        Route::get('/folders/{id}', [FolderController::class, 'getFolderDetail'])->name('folders.getFolderDetail.api');
        Route::put('/folders/{id}', [FolderController::class, 'update'])->name('folders.update.api');
        Route::delete('/folders/{id}', [FolderController::class, 'delete'])->name('folders.delete.api');
        Route::post('/folders/{id}/create', [FolderController::class, 'createFolder'])->name('folders.createFolder.api');
        Route::post('/folders/{id}/list', [FolderController::class, 'listFolder'])->name('folders.listFolder.api');
        
    });

    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });
});