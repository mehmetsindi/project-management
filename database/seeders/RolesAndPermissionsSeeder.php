<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'create tasks',
            'edit tasks',
            'delete tasks',
            'view budget',
            'manage budget',
            'manage meetings',
            'view meetings',
            'manage users',
            'manage projects',
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::create(['name' => $permission]);
        }

        // Create Roles and Assign Permissions
        $role = \Spatie\Permission\Models\Role::create(['name' => 'Super Admin']);
        $role->givePermissionTo(\Spatie\Permission\Models\Permission::all());

        $role = \Spatie\Permission\Models\Role::create(['name' => 'Project Manager']);
        $role->givePermissionTo(['create tasks', 'edit tasks', 'delete tasks', 'view budget', 'manage meetings', 'view meetings', 'manage projects']);

        $role = \Spatie\Permission\Models\Role::create(['name' => 'Team Member']);
        $role->givePermissionTo(['create tasks', 'edit tasks', 'view meetings']);

        $role = \Spatie\Permission\Models\Role::create(['name' => 'Client']);
        $role->givePermissionTo(['view meetings']);

        $role = \Spatie\Permission\Models\Role::create(['name' => 'Viewer']);
        // Viewers get no specific permissions by default, just read access where authorized

        // Create Departments
        $engineering = \App\Models\Department::create([
            'name' => 'Engineering',
            'description' => 'Software development and IT operations',
        ]);
        

        $marketing = \App\Models\Department::create([
            'name' => 'Marketing',
            'description' => 'Marketing and sales',
        ]);

        // Create Teams
        \App\Models\Team::create([
            'name' => 'Backend Team',
            'department_id' => $engineering->id,
            'description' => 'Responsible for server-side logic',
        ]);

        \App\Models\Team::create([
            'name' => 'Mobile Team',
            'department_id' => $engineering->id,
            'description' => 'Responsible for mobile apps',
        ]);

        \App\Models\Team::create([
            'name' => 'Growth Team',
            'department_id' => $marketing->id,
            'description' => 'Responsible for user acquisition',
        ]);
    }
}
