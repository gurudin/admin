<?php

Route::prefix('admin')->group(function () {
    Route::group(['namespace' => 'Controllers', 'middleware' => ['auth']], function () {
        Route::get('/', function () {
            return redirect()->route('get.group.select');
        });

        Route::get('welcome', function () {
            if (!request()->group) {
                return redirect()->route('get.group.select');
            }
            return view(config('admin.welcome_view'));
        })->name('get.welcome');

        Route::get('select', 'AuthGroupController@select')->name('get.group.select');

        Route::get('group', 'AuthGroupController@index')->name('get.group.index');
        Route::post('group', 'AuthGroupController@create')->name('post.group.create');
        Route::put('group', 'AuthGroupController@update')->name('put.group.update');
        Route::delete('group', 'AuthGroupController@destroy')->name('delete.group.destroy');

        Route::get('group/view/{id?}/{name?}', 'AuthGroupController@view')->name('get.group.view');

        Route::post('/groupChild', 'AuthGroupController@createChild')->name('post.group.createChild');
        Route::delete('/groupChild', 'AuthGroupController@destroyChild')->name('delete.group.destroyChild');

        Route::get('assignment', 'AuthAssignmentController@index')->name('get.assignment.index');
        Route::get('assignment/view/{id?}', 'AuthAssignmentController@view')->name('get.assignment.view');
        Route::post('assignment', 'AuthAssignmentController@create')->name('post.assignment.create');
        Route::delete('assignment', 'AuthAssignmentController@destroy')->name('delete.assignment.destroy');

        Route::get('route', 'AuthRouteController@index')->name('get.route.index');
        Route::post('route', 'AuthRouteController@create')->name('post.route.create');
        Route::delete('route', 'AuthRouteController@destroy')->name('delete.route.destroy');

        Route::get('permission', 'AuthPermissionController@index')->name('get.permission.index');
        Route::get('permission/create', 'AuthPermissionController@createView')->name('get.permission.create');
        Route::get('permission/update/{name?}/{desc?}', 'AuthPermissionController@updateView')->name('get.permission.update');
        Route::post('permission', 'AuthPermissionController@create')->name('post.permission.create');
        Route::put('permission', 'AuthPermissionController@update')->name('put.permission.update');
        Route::delete('permission', 'AuthPermissionController@destroy')->name('delete.permission.destroy');
        Route::get('permission/view/{name?}', 'AuthPermissionController@view')->name('get.permission.view');

        Route::post('batchPermission', 'AuthPermissionController@batchCreateRouteChild')
            ->name('post.permission.batchRouteChild');
        Route::delete('batchPermission', 'AuthPermissionController@batchRemoveRouteChild')
            ->name('delete.permission.batchRouteChild');

        Route::get('role', 'AuthRoleController@index')->name('get.role.index');
        Route::post('role', 'AuthRoleController@create')->name('post.role.create');
        Route::put('role', 'AuthRoleController@update')->name('put.role.update');
        Route::delete('role', 'AuthRoleController@destroy')->name('delete.role.destroy');

        Route::get('role/view/{name?}', 'AuthRoleController@view')->name('get.role.view');

        Route::get('menu', 'AuthMenuController@index')->name('get.menu.index');
        Route::get('menu/create', 'AuthMenuController@createView')->name('get.menu.create');
        Route::get('menu/update/{id?}', 'AuthMenuController@createView')->name('get.menu.update');
        Route::post('menu', 'AuthMenuController@create')->name('post.menu.create');
        Route::put('menu', 'AuthMenuController@update')->name('put.menu.update');
        Route::delete('menu', 'AuthMenuController@destroy')->name('delete.menu.destroy');
    });
});
