<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Exemplo\ExemploController;

Route::group(['namespace' => 'Exemplo', 'prefix' => 'exemplo'], function () {

    Route::post('/'                , [ExemploController::class,'create']);
    Route::put('/{id}'             , [ExemploController::class,'update']);
    Route::get('/all'              , [ExemploController::class,'list']);
    Route::get('/all/{skip}'       , [ExemploController::class,'list']);
    Route::get('/all/{skip}/{take}', [ExemploController::class,'list']);
    Route::delete('/{id}'          , [ExemploController::class,'delete']);
    Route::get('/{id}'             , [ExemploController::class,'getById']);

});
