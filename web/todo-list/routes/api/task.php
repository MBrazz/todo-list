<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Task\TaskController;

Route::group(['namespace' => 'Task', 'prefix' => 'task'], function () {

    Route::post('/'                , [TaskController::class,'create']);
    Route::get('/my-tasks'         , [TaskController::class,'getByUser']);
    Route::put('/{id}'             , [TaskController::class,'update']);
    Route::get('/all'              , [TaskController::class,'list']);
    Route::get('/all/{skip}'       , [TaskController::class,'list']);
    Route::get('/all/{skip}/{take}', [TaskController::class,'list']);
    Route::delete('/{id}'          , [TaskController::class,'delete']);
    Route::get('/{id}'             , [TaskController::class,'getById']);

});
