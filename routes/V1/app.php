<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\V1\Auth\AuthController;
use App\Http\Controllers\V1\HospitalController;
use App\Http\Controllers\V1\PeopleController;
use App\Http\Controllers\V1\FolderController;

use App\Http\Controllers\V1\KeyWordController;
use App\Http\Controllers\V1\DoctorController;
use App\Http\Controllers\V1\FamilyController;
use App\Http\Controllers\V1\Auth\GoogleController;
use App\Http\Controllers\V1\Auth\ConfirmPasswordController;
use App\Http\Controllers\V1\ScheduleController;
use App\Http\Controllers\V1\TagController;
use App\Http\Controllers\V1\AccountController;
use App\Http\Controllers\V1\ShareController;
use App\Http\Controllers\V1\RecordController;
use App\Http\Controllers\V1\RecordItemController;
use App\Http\Controllers\V1\MediaController;
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
        // Route::post('/signup', [AuthController::class, 'signup'])->name('signup.api');
        // Route::get('/oauth/google', [GoogleController::class, 'redirectToGoogle']);
        // Route::get('/oauth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

        Route::post('/register', [AuthController::class, 'register'])->name('register.api');
        Route::post('/google/create', [GoogleController::class, 'create'])->name('google.register.api');
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

        // SCHEDULE APIs
        Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.api');
        Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store.api');
        Route::get('/schedules/{id}', [ScheduleController::class, 'getScheduleDetail'])->name('schedules.detail.api');
        Route::put('/schedules/{id}', [ScheduleController::class, 'update'])->name('schedules.update.api');
        Route::delete('/schedules/{id}', [ScheduleController::class, 'delete'])->name('schedules.delete.api');

        Route::get('/schedules-list/{date}', [ScheduleController::class, 'getSchedule'])->name('schedules.getSchedule.api');


        // DOCTOR APIs
        Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors.api');
        Route::post('/doctors', [DoctorController::class, 'store'])->name('doctors.store.api');
        Route::get('/doctors/{id}', [DoctorController::class, 'detail'])->name('doctors.detail.api');
        Route::put('/doctors/{id}', [DoctorController::class, 'update'])->name('doctors.update.api');
        Route::delete('/doctors/{id}', [DoctorController::class, 'delete'])->name('doctors.delete.api');

        // FAMILY APIs
        Route::get('/family', [FamilyController::class, 'index'])->name('family.api');
        Route::post('/family', [FamilyController::class, 'store'])->name('family.store.api');
        Route::get('/family/{id}', [FamilyController::class, 'detail'])->name('family.detail.api');
        Route::put('/family/{id}', [FamilyController::class, 'update'])->name('family.update.api');
        Route::delete('/family/{id}', [FamilyController::class, 'delete'])->name('family.delete.api');

        // FOLDER APIs
        Route::get('/folders', [FolderController::class, 'index'])->name('folders.api');
        Route::post('/folders', [FolderController::class, 'store'])->name('folders.store.api');
        Route::get('/folders/{id}', [FolderController::class, 'getFolderDetail'])->name('folders.getFolderDetail.api');
        Route::put('/folders/{id}', [FolderController::class, 'update'])->name('folders.update.api');
        Route::delete('/folders/{id}', [FolderController::class, 'delete'])->name('folders.delete.api');
        Route::put('/folders/{id}/delete', [FolderController::class, 'deleteFolder'])->name('folders.deleteFolder.api');
        
        // MEDICINE APIs
        Route::get('/medicines', [KeywordController::class, 'index'])->name('medicines.api');
        Route::post('/medicines', [KeywordController::class, 'store'])->name('medicines.store.api');
        Route::get('/medicines/{id}', [KeywordController::class, 'detail'])->name('medicines.detail.api');
        Route::put('/medicines/{id}', [KeywordController::class, 'update'])->name('medicines.update.api');
        Route::delete('/medicines/{id}', [KeywordController::class, 'delete'])->name('medicines.delete.api');
        
        //TAGS APIs 
        Route::get('/tags', [TagController::class, 'index'])->name('tags.api');
        Route::post('/tags', [TagController::class, 'store'])->name('tags.store.api');
        Route::get('/tags/{id}', [TagController::class, 'getTagDetail'])->name('tags.detail.api');
        Route::put('/tags/{id}', [TagController::class, 'update'])->name('tags.update.api');
        Route::delete('/tags/{id}', [TagController::class, 'delete'])->name('tags.delete.api');

        //ACCOUNT APIs
        Route::get('/accounts/search', [AccountController::class, 'searchAccount'])->name('accounts.searchAccount.api');
        Route::get('/accounts/{id}', [AccountController::class, 'detail'])->name('accounts.detail.api');
        Route::put('/accounts/{id}', [AccountController::class, 'update'])->name('accounts.update.api');
        Route::delete('/accounts/{id}', [AccountController::class, 'delete'])->name('accounts.delete.api');

        //SHARES APIs
        Route::post('/shares', [ShareController::class, 'share'])->name('shares.share.api');
        Route::delete('/shares/{id}', [ShareController::class, 'delete'])->name('shares.delete.api');

        //RECORD
        Route::get('/records', [RecordController::class, 'index'])->name('records.api');
        Route::post('/records', [RecordController::class, 'store'])->name('records.store.api');
        Route::get('/records/{id}', [RecordController::class, 'detail'])->name('records.detail.api');
        Route::put('/records/{id}', [RecordController::class, 'update'])->name('records.update.api');
        Route::delete('/records/{id}', [RecordController::class, 'delete'])->name('records.delete.api');
        Route::get('/records/search', [RecordController::class, 'searchRecord'])->name('accounts.searchRecord.api');

        Route::put('/save-record/{id}', [RecordController::class, 'save'])->name('records.save.api');
        Route::post('/import', [RecordController::class, 'import'])->name('records.import.api');

        //RECORD_ITEM APIs
        Route::get('/record-item', [RecordItemController::class, 'index'])->name('record-item.api');
        Route::post('/record-item', [RecordItemController::class, 'store'])->name('record-item.store.api');
        Route::get('/record-item/{id}', [RecordItemController::class, 'detail'])->name('record-item.detail.api');
        Route::put('/record-item/{id}', [RecordItemController::class, 'update'])->name('record-item.update.api');
        Route::delete('/record-item/{id}', [RecordItemController::class, 'delete'])->name('record-item.delete.api');

        Route::get('/list-items/{id}', [RecordItemController::class, 'getItem'])->name('record-item.getItem.api');
        //MEDIA APIs
        Route::get('/medias', [MediaController::class, 'index'])->name('medias.api');
        Route::post('/medias', [MediaController::class, 'store'])->name('medias.store.api');
        Route::get('/medias/{id}', [MediaController::class, 'detail'])->name('medias.detail.api');
        Route::put('/medias/{id}', [MediaController::class, 'update'])->name('medias.update.api');
        Route::delete('/medias/{id}', [MediaController::class, 'delete'])->name('medias.delete.api');
    });


    
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });
});