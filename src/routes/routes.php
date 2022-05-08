<?php

use Illuminate\Support\Facades\Route;
use mradang\LaravelRbac\Controllers\RbacController;

Route::prefix('api/rbac')
    ->name('rbac.')
    ->middleware(['auth:sanctum', 'rbac'])
    ->controller(RbacController::class)
    ->group(function () {
        Route::post('allNodes', 'allNodes')->name('allNodes');
        Route::post('allRoles', 'allRoles')->name('allRoles');
        Route::post('createRole', 'createRole')->name('createRole');
        Route::post('deleteRole', 'deleteRole')->name('deleteRole');
        Route::post('updateRole', 'updateRole')->name('updateRole');
        Route::post('findRoleWithNodes', 'findRoleWithNodes')->name('findRoleWithNodes');
        Route::post('saveRoleSort', 'saveRoleSort')->name('saveRoleSort');
        Route::post('syncRoleNodes', 'syncRoleNodes')->name('syncRoleNodes');
    });
