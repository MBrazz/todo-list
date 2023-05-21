<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Comment\CommentController;

Route::group(['namespace' => 'Comment', 'prefix' => 'comment'], function () {

    Route::post('/'                , [CommentController::class,'create']);
    Route::get('/my-comments'      , [CommentController::class,'getByUser']);
    Route::put('/{id}'             , [CommentController::class,'update']);
    Route::get('/all'              , [CommentController::class,'list']);
    Route::get('/all/{skip}'       , [CommentController::class,'list']);
    Route::get('/all/{skip}/{take}', [CommentController::class,'list']);
    Route::delete('/{id}'          , [CommentController::class,'delete']);
    Route::get('/{id}'             , [CommentController::class,'getById']);

});
