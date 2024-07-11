<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\LoginController;
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

Route::post('/login', [LoginController::class, 'index']);


Route::group(['middleware' => 'auth:api'], function () {

    //logout
    Route::post('/logout', [\App\Http\Controllers\Api\LoginController::class, 'logout']);


    // categories
    Route::apiResource('/categories', \App\Http\Controllers\Api\CategoryController::class)
        ->middleware('permission:categories.index|categories.store|categories.update|categories.delete');

    // posts
    Route::apiResource('/posts', \App\Http\Controllers\Api\CategoryController::class)
        ->middleware('permission:posts.index|posts.store|posts.update|posts.delete');
});
