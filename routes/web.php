<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyInfoController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientHistoryController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\SpecializationController;
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
Route::middleware(['guest'])->group(function(){
    Route::get('/',[CompanyInfoController::class, 'show'])->name('heading');
});


Route::middleware(['auth'])->group(function(){
    Route::middleware(['admin'])->group(function(){
        Route::get('/settings',[CompanyInfoController::class, 'edit'])->name('settings')->middleware('password.confirm');
        Route::get('/settings/update/1')->name('settings_update')->middleware('password.confirm');
        Route::post('/settings/update/{company_info}',[CompanyInfoController::class, 'update']);

        Route::get('/department',[DepartmentController::class, 'index'])->name('department');
        Route::post('/department/{department}/edit',[DepartmentController::class, 'edit']);
        Route::post('/department/create',[DepartmentController::class, 'store']);
        Route::post('/department/{department}/update',[DepartmentController::class, 'update']);
        Route::delete('/department/{department}/delete',[DepartmentController::class, 'destroy']);


        Route::get('/tax',[TaxController::class, 'index'])->name('tax');
        Route::post('/tax/{tax}/edit',[TaxController::class, 'edit']);
        Route::post('/tax/create',[TaxController::class, 'store']);
        Route::post('/tax/{tax}/update',[TaxController::class, 'update']);
        Route::delete('/tax/{tax}/delete',[TaxController::class, 'destroy']);


        Route::get('/service',[ServiceController::class, 'index'])->name('service');
        Route::post('/service/{service}/edit',[ServiceController::class, 'edit']);
        Route::post('/service/create',[ServiceController::class, 'store']);
        Route::post('/service/{service}/update',[ServiceController::class, 'update']);
        Route::delete('/service/{service}/delete',[ServiceController::class, 'destroy']);

        Route::get('/schedule',[ScheduleController::class, 'index'])->name('schedule');
        Route::post('/schedule/{schedule}/edit',[ScheduleController::class, 'edit']);
        Route::post('/schedule/create',[ScheduleController::class, 'store']);
        Route::post('/schedule/{schedule}/update',[ScheduleController::class, 'update']);
        Route::delete('/schedule/{schedule}/delete',[ScheduleController::class, 'destroy']);

        Route::post('/specialization/{specialization}/edit',[SpecializationController::class, 'edit']);
        Route::post('/specialization/create',[SpecializationController::class, 'store']);
        Route::post('/specialization/{specialization}/update',[SpecializationController::class, 'update']);
        Route::delete('/specialization/{specialization}/delete',[SpecializationController::class, 'destroy']);
        Route::get('/specialization',[SpecializationController::class, 'index'])->name('specialization');

        Route::get('/user',[UserController::class, 'index'])->name('user');
        Route::post('/user/create',[UserController::class, 'store']);
        Route::get('/user/create',[UserController::class, 'store']);
        Route::post('/user/{user}/edit',[UserController::class, 'edit']);
        Route::delete('/user/{user}/delete',[UserController::class, 'destroy']);
        Route::put('/user/{user}/update', [UserController::class, 'update']);

        Route::get('/doctor',[DoctorController::class, 'index'])->name('doctor');
        Route::post('/doctor/{doctor}/show',[DoctorController::class, 'show']);
        Route::post('/doctor/{doctor}/edit',[DoctorController::class, 'edit']);
        Route::post('/doctor/create',[DoctorController::class, 'store']);
        Route::post('/doctor/{doctor}/update',[DoctorController::class, 'update']);
        Route::delete('/doctor/{doctor}/delete',[DoctorController::class, 'destroy']);
    });
    Route::get('/dashboard',[CompanyInfoController::class, 'index'])->name('dashboard');

    Route::post('/user/{user}/update',[UserController::class, 'update'])->name('user.update');
    Route::get('/profile',[UserController::class, 'create'])->name('profile');
    Route::get('/change_password',[UserController::class, 'show'])->name('password');
    Route::post('/change_password/{user}',[UserController::class, 'password']);


    Route::get('/patient',[PatientController::class, 'index'])->name('patient');
    Route::post('/patient/status',[PatientController::class, 'create'])->name('patient_status');
    Route::post('/patient/list',[AppointmentController::class, 'create']);
    Route::post('/patient/{patient}/show',[PatientController::class, 'show']);
    Route::post('/patient/{patient}/edit',[PatientController::class, 'edit']);
    Route::post('/patient/create',[PatientController::class, 'store']);
    Route::post('/patient/{patient}/update',[PatientController::class, 'update']);
    Route::delete('/patient/{patient}/delete',[PatientController::class, 'destroy']);

    Route::get('/appointment',[AppointmentController::class, 'index'])->name('appointment');
    Route::match(['post','get'],'/appointment/list',[AppointmentController::class, 'create']);
    Route::match(['post','get'],'/appointment/{appointment}/edit',[AppointmentController::class, 'edit']);
    Route::post('/appointment/{appointment}/show',[AppointmentController::class, 'show']);
    Route::post('/appointment/create',[AppointmentController::class, 'store']);
    Route::post('/appointment/{appointment}/update',[AppointmentController::class, 'update']);
    Route::delete('/appointment/{appointment}/delete',[AppointmentController::class, 'destroy']);

    

});

require __DIR__.'/auth.php';
Route::middleware(['auth'])->group(function(){
    Route::get('/{page}', function ($page) {
        // Check if the view file exists
        if (view()->exists($page)) {
            // Return the view
            return view($page);
        } else {
           
            // The view file does not exist
            abort(404);
        }
    });
});
