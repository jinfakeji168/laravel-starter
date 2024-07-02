<?php

use App\Http\Controllers\Api\Admin\OperationLogController;
use App\Http\Controllers\Api\Admin\PermissionController;
use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Middleware\LogOperations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::middleware([
        'auth:sanctum',
        'role:admin',
        LogOperations::class,
    ])->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('roles', RoleController::class);
        Route::apiResource('permissions', PermissionController::class);
        Route::apiResource('operation-logs', OperationLogController::class)->only(['index', 'destroy']);
    });
});
