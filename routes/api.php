<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchhController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectTypeController;
use App\Http\Controllers\DonerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\SliderController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group([  'middleware' => 'api','prefix' => 'auth'], function ($router) {
    Route::post('/login',  [AuthController::class,'login']);
    Route::post('/register', [AuthController::class,'register']);
    Route::get('/logout',  [AuthController::class,'logout']);
    Route::post('/refresh',  [AuthController::class,'refresh']);
    Route::post('/reset_password',  [AuthController::class,'reset_password'] );
});
Route::controller(UserController::class)->prefix('user')->group(function (){
    
    Route::get('/','index');
    Route::get('/request','requests');
    Route::get('/show','show');
    Route::get('/get_volunter','get');
    Route::get('/volunter','get_accepted_volunter');
    Route::post('/add','store')->middleware('api');
    Route::delete('/delete','destroy');
    Route::post('/update','update');
    Route::post('/accept','accept');
    Route::post('/search','search');
    Route::post('/attach','attach_user_to_project');
    // route belongto account
    Route::get('/get_accounts','get_accounts');
    Route::post('/type','set_account_type');
    Route::Post('/search_account','search_account');
    
    
});
Route::controller(ProjectController::class)->prefix('project')->group(function (){
    
    Route::get('/{num_pages?}','index');
    Route::get('/type_department','get_type_department');
    Route::post('/add','store');
    Route::delete('/delete/{id}','destroy');
    Route::post('/update','update');
    Route::post('/show','show');
    Route::post('/show_all_detailes','show_all_detailes');
    Route::post('/search','search');
    
    
});
Route::controller(DonerController::class)->prefix('doner')->group(function (){
    
    Route::get('/','index');
    Route::post('/add','store');
    Route::delete('/delete/{id}','destroy');
    Route::post('/update','update');
    Route::get('/show','show');
    Route::post('/attach','attach_doner_to_project');
    Route::post('/search','search');
    
    
});
Route::controller(EmployeeController::class)->prefix('employee')->group(function (){
    
    Route::get('/','index');
    Route::get('/request','request');
    Route::post('/add','store');
    Route::delete('/delete','destroy');
    Route::post('/update','update');
    Route::post('/accept','accept');
    Route::post('/show','show');
    Route::get('/search/{search}','search');
    Route::post('/attach','attach_doner_to_project');
    
});
Route::controller(ProjectTypeController::class)->prefix('projectType')->group(function (){
    
    Route::get('/','index');
    Route::post('/add','store')->middleware('api');
    Route::delete('/{id}','destroy');
    Route::post('/{id}','update');
    
    
});
Route::controller(SliderController::class)->prefix('slider')->group(function (){
    
    Route::get('/','index');
    Route::post('/add','store');
    Route::delete('/','destroy');
    Route::post('/','update');
    
    
});
Route::controller(WorkController::class)->prefix('work')->group(function (){
    
    Route::get('/','index');
    Route::post('/add','store')->middleware('api');
    Route::delete('/delete/{id}','destroy');
    Route::post('/update','update');
    
    
});
Route::controller(BranchController::class)->prefix('branch')->group(function (){
    
    Route::get('/','index');
    Route::post('/add','store');
    Route::delete('/delete/{id}','destroy');
    Route::post('/update','update');
    
    
});