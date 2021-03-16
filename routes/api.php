<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {

    Route::post('login', [AuthController::class,'login']);
    Route::post('profile', [AuthController::class,'profile']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('me', [AuthController::class,'me']);

});

Route::group(['prefix' => 'admin', 'middleware' => 'roles:Admin'], function () {

//    staff routes
    Route::post('add/staff', [StaffController::class,'addStaff']);
    Route::get('all/staff', [StaffController::class,'allStaff']);
    Route::get('specific/staff', [StaffController::class,'specificStaff']);
    Route::post('update/staff', [StaffController::class,'updateStaff']);
    Route::post('delete/staff', [StaffController::class,'deleteStaff']);

});

Route::group(['prefix' => 'dashboard', 'middleware' => 'roles:Admin.Support.Secretary'], function () {

//    teacher routes
    Route::post('add/teacher', [TeacherController::class,'addTeacher']);
    Route::get('all/teacher', [TeacherController::class,'allTeacher']);
    Route::get('specific/teacher', [TeacherController::class,'specificTeacher']);
    Route::post('update/teacher', [TeacherController::class,'updateTeacher']);
    Route::post('delete/teacher', [TeacherController::class,'deleteTeacher']);

//    student routes
    Route::post('add/student', [StudentController::class,'addStudent']);
    Route::get('all/student', [StudentController::class,'allStudent']);
    Route::get('specific/student', [StudentController::class,'specificStudent']);
    Route::post('update/student', [StudentController::class,'updateStudent']);
    Route::post('delete/student', [StudentController::class,'deleteStudent']);

//    group routes
    Route::post('add/group', [GroupController::class,'addGroup']);
    Route::get('all/group', [GroupController::class,'allGroup']);
    Route::get('specific/group', [GroupController::class,'specificGroup']);
    Route::post('update/group', [GroupController::class,'updateGroup']);
    Route::post('delete/group', [GroupController::class,'deleteGroup']);


    //    session routes
    Route::post('add/session', [SessionController::class,'addSession']);
    Route::get('all/session', [SessionController::class,'allSession']);
    Route::get('specific/session', [SessionController::class,'specificSession']);
    Route::post('update/session', [SessionController::class,'updateSession']);
    Route::post('delete/session', [SessionController::class,'deleteSession']);



    //    complaint routes
    Route::get('all/complaint', [ComplaintController::class,'allComplaint']);
    Route::get('specific/complaint', [ComplaintController::class,'specificComplaint']);
    Route::post('delete/complaint', [ComplaintController::class,'deleteComplaint']);


    //    subscription routes
    Route::get('limit/subscription', [SubscriptionController::class,'limitSubscription']);
    Route::get('closed/subscription', [SubscriptionController::class,'closedSubscription']);


});



