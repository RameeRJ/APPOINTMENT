<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;


Route::get('/', function () {
    return view('index');
});

Route::get('/{any}' , [AppController::class, 'index'])->where('any', '.*');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

//admin functions

Route::prefix('departments')->group(function () {
    Route::post('/', [AdminController::class, 'DepartmentIndex']);
    Route::post('/add', [AdminController::class, 'DepartmentStore']);
    Route::delete('/delete/{id}', [AdminController::class, 'DepartmentDestroy']);
});
Route::prefix('doctors')->group(function () {
Route::post('/', [AdminController::class, 'DoctorIndex']); 
Route::post('/add', [AdminController::class, 'DoctorStore']); 
Route::delete('/delete/{id}', [AdminController::class, 'DoctorDestroy']);
});



//doctor functions


Route::post('/schedules/{doctorId}', [DoctorController::class, 'ScheduleIndex']);
Route::post('/add', [DoctorController::class, 'ScheduleStore']);
Route::delete('/schedules/{id}', [DoctorController::class, 'ScheduleDestroy']);
Route::post('/get-doctorid', [DoctorController::class, 'GetDoctorId']);
Route::post('/appointments', [DoctorController::class, 'GetAppointments']);
Route::post('/appointments-by-schedule/{doctorId}', [DoctorController::class, 'AppointmentBySchedule']);
Route::post('/appointments/{id}/prescription', [DoctorController::class, 'PrescriptionStore']);


//patient functions

Route::prefix('patient')->group(function () {
    Route::post('/schedules', [PatientController::class, 'ScheduleIndex']);
    Route::post('/appointment', [PatientController::class, 'AppointmentStore']);
    Route::post('/check-appointment-limit', [PatientController::class, 'checkLimit']);
    Route::post('/user-details', [PatientController::class, 'getUserDetails']);
    Route::post('/update-profile', [PatientController::class, 'updateProfile']);
});
Route::post('/appointments/{userId}', [PatientController::class, 'getAppointmentsByUser']);
Route::delete('/appointments/{id}', [PatientController::class, 'AppoinmentDestroy']);
Route::post('/user/profile-picture', [PatientController::class, 'getProfilePicture']);