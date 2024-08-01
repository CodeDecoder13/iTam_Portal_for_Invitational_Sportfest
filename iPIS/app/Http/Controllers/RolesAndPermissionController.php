<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionController extends Controller
{
    public function addPermissions()
    {
        // Define an array of permissions to be added
        $permissions = [
            'create user',
            'edit user',
            'delete user',
            'view user',
            'create role',
            'edit role',
            'delete role',
            'view role',
            'create permission',
            'edit permission',
            'delete permission',
            'view permission',
        ];

        // Define an array of roles to be created or found
        $roles = ['admin', 'SADO', 'RAC'];

        // Create or find each role and assign permissions
        foreach ($roles as $roleName) {
            // Create or find the role with guard_name 'admin'
            $role = Role::firstOrCreate(
                ['name' => $roleName],
                ['guard_name' => 'admin']
            );

            // Loop through the array of permissions
            foreach ($permissions as $permissionName) {
                // Create or find each permission in the database with guard_name 'admin'
                $permission = Permission::firstOrCreate(
                    ['name' => $permissionName],
                    ['guard_name' => 'admin']
                );

                // Assign the permission to the role
                $role->givePermissionTo($permission);
            }
        }

        // Return a JSON response indicating success
        return response()->json(['message' => 'Permissions added and assigned to roles successfully']);
    }
}