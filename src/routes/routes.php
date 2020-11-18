<?php

use Illuminate\Support\Facades\Route;
use mradang\LaravelRbac\Controllers\RbacNodeController;
use mradang\LaravelRbac\Controllers\RbacRoleController;

Route::group([
    'prefix' => 'api',
    'middleware' => ['auth'],
], function () {
    Route::group(['prefix' => 'rbac'], function () {
        Route::post('allNodes', [RbacNodeController::class, 'all']);
        Route::post('allNodesWithRole', [RbacNodeController::class, 'allWithRole']);
        Route::post('refreshNodes', [RbacNodeController::class, 'refresh']);
        Route::post('syncNodeRoles', [RbacNodeController::class, 'syncRoles']);

        Route::post('allRoles', [RbacRoleController::class, 'all']);
        Route::post('createRole', [RbacRoleController::class, 'create']);
        Route::post('deleteRole', [RbacRoleController::class, 'delete']);
        Route::post('findRoleWithNodes', [RbacRoleController::class, 'findWithNodes']);
        Route::post('saveRoleSort', [RbacRoleController::class, 'saveSort']);
        Route::post('syncRoleNodes', [RbacRoleController::class, 'syncNodes']);
        Route::post('updateRole', [RbacRoleController::class, 'update']);
    });
});
