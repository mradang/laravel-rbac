<?php

use Illuminate\Support\Facades\Route;
use mradang\LaravelRbac\Controllers\RbacNodeController;
use mradang\LaravelRbac\Controllers\RbacRoleController;

Route::group([
    'prefix' => 'api',
    'middleware' => ['auth:sanctum', 'rbac'],
], function () {
    Route::group(['prefix' => 'rbac'], function () {
        Route::post('allNodes', [RbacNodeController::class, 'all']);
        Route::post('allRoles', [RbacRoleController::class, 'all']);
        Route::post('createRole', [RbacRoleController::class, 'create']);
        Route::post('deleteRole', [RbacRoleController::class, 'delete']);
        Route::post('updateRole', [RbacRoleController::class, 'update']);
        Route::post('findRoleWithNodes', [RbacRoleController::class, 'findWithNodes']);
        Route::post('saveRoleSort', [RbacRoleController::class, 'saveSort']);
        Route::post('syncRoleNodes', [RbacRoleController::class, 'syncNodes']);
    });
});
