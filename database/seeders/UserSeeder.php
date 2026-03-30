<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Menu;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create roles and assign existing permissions
        Role::firstOrCreate(['name' => 'Super Admin','guard_name'=>'web','level'=>0]);

        // create menu
        $dashboard = Menu::create(['title' => 'Dashboard', 'url' => 'home']);
        $administration = Menu::updateOrCreate(['title' => 'Administration']);
        $menuPage = $administration->children()->updateOrCreate(['title' => 'Menu', 'url' => 'menu.index', 'icon' => 'fa fa-bars']);
        $rolesPage = $administration->children()->updateOrCreate(['title' => 'Roles', 'url' => 'roles.index', 'icon' => 'fa fa-user-plus']);
        $permissionsPage = $administration->children()->updateOrCreate(['title' => 'Permissions', 'url' => 'permissions.index']);
        $usersPage = $administration->children()->updateOrCreate(['title' => 'Users', 'url' => 'users.index', 'icon' => 'fa fa-users']);
        $activityLogPage = $administration->children()->updateOrCreate(['title' => 'Activity Log', 'url' => 'activity-logs.index', 'icon' => 'fa fa-history']);

        Permission::firstOrCreate(['name' => 'users index', 'guard_name' => 'web', 'menu_id' => $usersPage->id]);
        Permission::firstOrCreate(['name' => 'users create', 'guard_name' => 'web', 'menu_id' => $usersPage->id]);
        Permission::firstOrCreate(['name' => 'users edit', 'guard_name' => 'web', 'menu_id' => $usersPage->id]);
        Permission::firstOrCreate(['name' => 'users delete', 'guard_name' => 'web', 'menu_id' => $usersPage->id]);
        Permission::firstOrCreate(['name' => 'landing page assign', 'guard_name' => 'web', 'menu_id' => $usersPage->id]);
        Permission::firstOrCreate(['name' => 'reset attempts', 'guard_name' => 'web', 'menu_id' => $usersPage->id]);
        Permission::firstOrCreate(['name' => 'reset password', 'guard_name' => 'web', 'menu_id' => $usersPage->id]);

        Permission::firstOrCreate(['name' => 'permissions index', 'guard_name' => 'web', 'menu_id' => $permissionsPage->id]);
        Permission::firstOrCreate(['name' => 'permissions create', 'guard_name' => 'web', 'menu_id' => $permissionsPage->id]);
        Permission::firstOrCreate(['name' => 'permissions edit', 'guard_name' => 'web', 'menu_id' => $permissionsPage->id]);
        Permission::firstOrCreate(['name' => 'permissions delete', 'guard_name' => 'web', 'menu_id' => $permissionsPage->id]);

        Permission::firstOrCreate(['name' => 'roles index', 'guard_name' => 'web', 'menu_id' => $rolesPage->id]);
        Permission::firstOrCreate(['name' => 'roles create', 'guard_name' => 'web', 'menu_id' => $rolesPage->id]);
        Permission::firstOrCreate(['name' => 'roles edit', 'guard_name' => 'web', 'menu_id' => $rolesPage->id]);
        Permission::firstOrCreate(['name' => 'roles delete', 'guard_name' => 'web', 'menu_id' => $rolesPage->id]);

        Permission::firstOrCreate(['name' => 'menu index', 'guard_name' => 'web', 'menu_id' => $menuPage->id]);
        Permission::firstOrCreate(['name' => 'menu create', 'guard_name' => 'web', 'menu_id' => $menuPage->id]);
        Permission::firstOrCreate(['name' => 'menu edit', 'guard_name' => 'web', 'menu_id' => $menuPage->id]);
        Permission::firstOrCreate(['name' => 'menu delete', 'guard_name' => 'web', 'menu_id' => $menuPage->id]);

        Permission::firstOrCreate(['name' => 'activity-logs index', 'guard_name' => 'web', 'menu_id' => $activityLogPage->id]);
        Permission::firstOrCreate(['name' => 'activity-logs create', 'guard_name' => 'web', 'menu_id' => $activityLogPage->id]);
        Permission::firstOrCreate(['name' => 'activity-logs edit', 'guard_name' => 'web', 'menu_id' => $activityLogPage->id]);
        Permission::firstOrCreate(['name' => 'activity-logs delete', 'guard_name' => 'web', 'menu_id' => $activityLogPage->id]);

        Permission::firstOrCreate(['name' => 'dashboard index', 'guard_name' => 'web', 'menu_id' => $dashboard->id]);

        $adminRole = Role::firstOrCreate(['name' => 'Admin','guard_name'=>'web','level'=>1]);
        $adminRole->givePermissionTo([
            'users index',
            'users create',
            'users edit',
            'users delete',
            'landing page assign',
            'dashboard index'
        ]);

        // Create super-admin user
        $user = User::firstOrCreate([
            'name' => 'test_super@mylinex.com',
            'email' => 'test_super@mylinex.com',
            'password' => Hash::make('test_super@mylinex.com'),
            'landing_page' => 'home'
        ]);
        $user->assignRole('Super Admin');

        // Create admin user
        $user = User::firstOrCreate([
            'name' => 'test_admin@mylinex.com',
            'email' => 'test_admin@mylinex.com',
            'password' => Hash::make('test_admin@mylinex.com'),
            'landing_page' => 'home'
        ]);
        $user->assignRole('Admin');
    }
}
