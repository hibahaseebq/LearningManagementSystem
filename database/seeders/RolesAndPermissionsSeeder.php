<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder

{
    /**

     * Run the database seeds.

     *

     * @return void

     */

    public function run()

    {

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();



        $permissions = [

            'add user',

            'assign role',

            'approve/reject student',

            'assign quiz',

            'view students',

            'view quiz attempts',

            'manage quizzes',

            'view assigned quizzes',

            'attempt quizzes',

            'view results',

        ];

        foreach ($permissions as $permission) {

            Permission::firstOrCreate(['name' => $permission]);

        }

       $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $managerRole->givePermissionTo(['assign quiz', 'view students', 'view quiz attempts']);

        $studentRole = Role::firstOrCreate(['name' => 'student']);
        $studentRole->givePermissionTo(['view assigned quizzes', 'attempt quizzes', 'view results']);

        $supervisorRole = Role::firstOrCreate(['name' => 'supervisor']);
        $supervisorRole->givePermissionTo(['assign quiz', 'view students', 'view quiz attempts', 'manage quizzes']);



    }

}