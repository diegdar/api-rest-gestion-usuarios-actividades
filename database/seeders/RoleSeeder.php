<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::create(["name"=> "Admin"]);
        $userRole = Role::create(["name"=> "User"]);

        // Roles for users
        Permission::create(["name"=> "user.create"])->assignRole($userRole);
        Permission::create(["name"=> "user.login"])->assignRole($userRole);
        Permission::create(["name"=> "user.activity.join"])->assignRole($userRole);
        
        // Roles for users and admins
        Permission::create(["name"=> "user.details.get"])->syncRoles([$adminRole, $userRole]);
        Permission::create(["name"=> "user.update"])->syncRoles([$adminRole, $userRole]);
        Permission::create(["name"=> "user.delete"])->syncRoles([$adminRole, $userRole]);

        // Roles for admins
        Permission::create(["name"=> "activity.create"])->assignRole($adminRole);
        Permission::create(["name"=> "activity.details.get"])->assignRole($adminRole);
        Permission::create(["name"=> "activities.export"])->assignRole($adminRole);
        Permission::create(["name"=> "activities.import"])->assignRole($adminRole);


    }
}
