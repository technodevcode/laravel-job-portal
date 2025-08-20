<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\admin\AdminDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jobs', [JobsController::class, 'index'])->name('jobs');
Route::get('/jobs/detail/{id}',[JobsController::class,'jobDetialPage'])->name('jobDetialPage');
Route::post('/apply-job',[JobsController::class,'applyJob'])->name('applyJob');
Route::post('/save-job',[JobsController::class,'saveJob'])->name('saveJob');


Route::group(['prefix' => 'account'], function(){
    Route::get('/forgot-password',[AccountController::class,'forgotPassword'])->name('account.forgotPassword');
    Route::post('/process-forgot-password',[AccountController::class,'processForgotPassword'])->name('account.processForgotPassword');
    Route::get('/reset-password/{token}',[AccountController::class,'resetPassword'])->name('account.resetPassword');
    Route::post('/process-reset-password',[AccountController::class,'processResetPassword'])->name('account.processResetPassword');
});


Route::group(['prefix' => 'admin', 'middleware' => 'role', 'as' => 'admin.'], function(){
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users',[AdminDashboardController::class,'usersList'])->name('users');
    Route::get('/users/{id}',[AdminDashboardController::class,'editUser'])->name('users.edit');
    Route::put('/users/{id}',[AdminDashboardController::class,'updateUser'])->name('users.update');
    Route::delete('/delet-users',[AdminDashboardController::class,'destroyUser'])->name('users.destroy');
    
    Route::get('/jobs',[AdminDashboardController::class,'jobsList'])->name('jobs');
    Route::get('/jobs/edit/{id}',[AdminDashboardController::class,'editJob'])->name('jobs.edit');
    Route::put('/jobs/{id}',[AdminDashboardController::class,'updateJob'])->name('jobs.update');
    Route::delete('/delete-jobs',[AdminDashboardController::class,'destroyJob'])->name('jobs.destroy');
    Route::patch('/jobs/{id}', [AdminDashboardController::class, 'restoreJob'])->name('jobs.restore');
    Route::get('/job-applications',[AdminDashboardController::class,'jobApplications'])->name('jobApplications');
    Route::delete('/delet-job-applications',[AdminDashboardController::class,'destroy'])->name('jobApplications.destroy');
    
});  

Route::group(['prefix' => 'account'], function(){
    // guest
    Route::group(['middleware' => 'guest'], function(){
        Route::post('/process-register', [AccountController::class, 'processRegistration'])->name('account.processRegistration');
        Route::get('/login', [AccountController::class, 'login'])->name('account.login');
        Route::get('/register', [AccountController::class, 'registration'])->name('account.registration');
        Route::post('/authenticate', [AccountController::class, 'authenticate'])->name('account.authenticate');
    });
    
    //Authenticated Routes for those page which accesable after login
    Route::group(['middleware' => 'auth'], function(){
        Route::get('/profile', [AccountController::class, 'profile'])->name('account.profile');
        Route::get('/logout', [AccountController::class, 'logout'])->name('account.logout');
        Route::put('/update-profile', [AccountController::class, 'updateProfile'])->name('account.updateProfile');
        Route::post('/update-profile-pic', [AccountController::class, 'updateProfilePic'])->name('account.updateProfilePic');
        Route::get('/create-job', [AccountController::class, 'createJob'])->name('account.createJob');
        Route::post('/save-job', [AccountController::class, 'saveJob'])->name('account.saveJob');
        Route::get('/my-jobs', [AccountController::class, 'myJobs'])->name('account.myJobs');
        Route::get('/my-jobs/edit/{jobid}', [AccountController::class, 'editJob'])->name('account.editJob');
        Route::post('/update-job/{jobid}', [AccountController::class, 'updateJob'])->name('account.updateJob');
        Route::post('/delete-job/', [AccountController::class, 'deleteJob'])->name('account.deleteJob');
        Route::get('/my-job-applications',[AccountController::class,'myJobApplications'])->name('account.myJobApplications'); 
        Route::post('/remove-job-application/', [AccountController::class, 'appliedJobDelete'])->name('account.appliedJobDelete'); 
        Route::get('/saved-jobs',[AccountController::class,'savedJobs'])->name('account.savedJobs');  
        Route::post('/remove-saved-job',[AccountController::class,'removeSavedJob'])->name('account.removeSavedJob'); 
        Route::post('/update-password',[AccountController::class,'updatePassword'])->name('account.updatePassword');
    });
});

