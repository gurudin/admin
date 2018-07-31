<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class InitAdminData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Table users
         * name=admin@admin.com
         * password=admin123
         */
        $uid = DB::table('users')->insertGetId([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => '$2y$10$9o7WbYZ6f0UlrVngHUrrnOZlrH5YQCBsH8t3.l/v7IsICSvVG2SCW'
        ]);

        // auth_group
        $gid = DB::table('auth_group')->insertGetId(['name' => 'Default', 'description' => 'default']);

        // auth_group_child
        DB::table('auth_group_child')->insert(['group_id' => $gid, 'child' => $uid, 'type' => 1]);

        // auth_item
        $auth_item_data = [
            ['name' => '/admin/assignment', 'method' => 'any', 'type' => 2, 'description' => null],
            ['name' => '/admin/assignment/view/{id?}', 'method' => 'get', 'type' => 2, 'description' => null],
            ['name' => '/admin/batchPermission', 'method' => 'delete', 'type' => 2, 'description' => null],
            ['name' => '/admin/batchPermission', 'method' => 'post', 'type' => 2, 'description' => null],
            ['name' => '/admin/group', 'method' => 'any', 'type' => 2, 'description' => null],
            ['name' => '/admin/group/view/{id?}/{name?}', 'method' => 'get', 'type' => 2, 'description' => null],
            ['name' => '/admin/groupChild', 'method' => 'delete', 'type' => 2, 'description' => null],
            ['name' => '/admin/groupChild', 'method' => 'post', 'type' => 2, 'description' => null],
            ['name' => '/admin/menu', 'method' => 'any', 'type' => 2, 'description' => null],
            ['name' => '/admin/menu/create', 'method' => 'get', 'type' => 2, 'description' => null],
            ['name' => '/admin/permission', 'method' => 'any', 'type' => 2, 'description' => null],
            ['name' => '/admin/permission/create', 'method' => 'get', 'type' => 2, 'description' => null],
            ['name' => '/admin/permission/view/{name?}', 'method' => 'get', 'type' => 2, 'description' => null],
            ['name' => '/admin/role', 'method' => 'any', 'type' => 2, 'description' => null],
            ['name' => '/admin/route', 'method' => 'any', 'type' => 2, 'description' => null],
            ['name' => '/admin/update/{id?}', 'method' => 'get', 'type' => 2, 'description' => null],
            ['name' => '/admin/view/{name?}', 'method' => 'get', 'type' => 2, 'description' => null],
            ['name' => '/admin/welcome', 'method' => 'get', 'type' => 2, 'description' => null],
            ['name' => 'assignment', 'method' => '', 'type' => 2, 'description' => 'Assignment'],
            ['name' => 'permission-role', 'method' => '', 'type' => 1, 'description' => 'System role'],
            ['name' => 'permisson', 'method' => '', 'type' => 2, 'description' => 'Systems & Permssions'],
        ];
        DB::table('auth_item')->insert($auth_item_data);

        // auth_item_child
        $auth_item_child_data = [
            ['parent' => 'assignment', 'method' => 'any', 'child' => '/admin/assignment'],
            ['parent' => 'assignment', 'method' => 'get', 'child' => '/admin/assignment/view/{id?}'],
            ['parent' => 'permission-role', 'method' => '', 'child' => 'assignment'],
            ['parent' => 'permission-role', 'method' => '', 'child' => 'permisson'],
            ['parent' => 'permisson', 'method' => 'any', 'child' => '/admin/group'],
            ['parent' => 'permisson', 'method' => 'any', 'child' => '/admin/menu'],
            ['parent' => 'permisson', 'method' => 'any', 'child' => '/admin/permission'],
            ['parent' => 'permisson', 'method' => 'any', 'child' => '/admin/role'],
            ['parent' => 'permisson', 'method' => 'any', 'child' => '/admin/route'],
            ['parent' => 'permisson', 'method' => 'delete', 'child' => '/admin/batchPermission'],
            ['parent' => 'permisson', 'method' => 'delete', 'child' => '/admin/groupChild'],
            ['parent' => 'permisson', 'method' => 'get', 'child' => '/admin/group/view/{id?}/{name?}'],
            ['parent' => 'permisson', 'method' => 'get', 'child' => '/admin/menu/create'],
            ['parent' => 'permisson', 'method' => 'get', 'child' => '/admin/permission/create'],
            ['parent' => 'permisson', 'method' => 'get', 'child' => '/admin/permission/view/{name?}'],
            ['parent' => 'permisson', 'method' => 'get', 'child' => '/admin/update/{id?}'],
            ['parent' => 'permisson', 'method' => 'get', 'child' => '/admin/view/{name?}'],
            ['parent' => 'permisson', 'method' => 'get', 'child' => '/admin/welcome'],
            ['parent' => 'permisson', 'method' => 'post', 'child' => '/admin/batchPermission'],
            ['parent' => 'permisson', 'method' => 'post', 'child' => '/admin/groupChild'],
        ];
        DB::table('auth_item_child')->insert($auth_item_child_data);

        // auth_assignment
        DB::table('auth_assignment')->insert(['item_name' => 'permission-role', 'group_id' => $gid, 'user_id' => $uid]);

        // menu
        $menu_data = [
            ['title' => 'System', 'parent' => null, 'route' => '', 'order' => 0, 'data' => '{"icon": "fas fa-align-justify"}'],
            ['title' => 'Menu', 'parent' => '1', 'route' => '', 'order' => 0, 'data' => ''],
            ['title' => 'List', 'parent' => '2', 'route' => '/admin/menu', 'order' => 0, 'data' => ''],
            ['title' => 'Create', 'parent' => '2', 'route' => '/admin/menu/create', 'order' => 0, 'data' => ''],
            ['title' => 'Permissions', 'parent' => '1', 'route' => '', 'order' => 0, 'data' => ''],
            ['title' => 'Route', 'parent' => '5', 'route' => '/admin/route', 'order' => 0, 'data' => ''],
            ['title' => 'Permission', 'parent' => '5', 'route' => '/admin/permission', 'order' => 0, 'data' => ''],
            ['title' => 'Role', 'parent' => '5', 'route' => '/admin/role', 'order' => 0, 'data' => ''],
            ['title' => 'Group', 'parent' => '1', 'route' => '/admin/group', 'order' => 0, 'data' => ''],
            ['title' => 'Assignment', 'parent' => '1', 'route' => '/admin/assignment', 'order' => 0, 'data' => ''],
        ];
        DB::table('menu')->insert($menu_data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
