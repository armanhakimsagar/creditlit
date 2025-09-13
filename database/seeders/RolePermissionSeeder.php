<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Permission List as array
        $permissions = [
            [
                'group_name' => 'Class',
                'permissions' => [
                    'Class Index',
                    'Class Create',
                    'Class Store',
                    'Class Edit',
                    'Class Update',
                    'Class Delete',
                ],
            ],
            [
                'group_name' => 'Student',
                'permissions' => [
                    'Student Index',
                    'Student Create',
                    'Student Store',
                    'Student Edit',
                    'Student Update',
                ],
            ],
        ];

        // Create Permission
        for ($i = 0; $i < count($permissions); $i++) {
            $permissionGroup = $permissions[$i]['group_name'];
            for ($j = 0; $j < count($permissions[$i]['permissions']); $j++) {
                $permission = Permission::create([
                    'name' => $permissions[$i]['permissions'][$j],
                    'group_name' => $permissionGroup,
                    'slug' => Str::slug($permissions[$i]['permissions'][$j], '.'),
                ]);
            }
        }
    }
}
