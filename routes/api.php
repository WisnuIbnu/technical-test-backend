<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LeaveApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']); 

Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/list-cuti', [LeaveApiController::class, 'index']);
    Route::post('/pengajuan-cuti', [LeaveApiController::class, 'store']);
    
    Route::put('/admin/verifikasi-cuti/{id}/action', [LeaveApiController::class, 'updateStatus']);


    Route::post('/logout', [AuthController::class, 'logout']);
});
