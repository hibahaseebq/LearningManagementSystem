<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;


class AdminSeeder extends Seeder

{

    /**

     * Run the database seeds.

     *

     * @return void

     */

    public function run()

    {

        $adminRole = Role::where("name","admin")->first();



        if(!$adminRole) {

            $this->call(RolesAndPermissionsSeeder::class);

            $adminRole = Role::where("name","admin")->first();

        }

       

        $adminuser = User::updateOrCreate(

            ['email' => 'admin@example.com'],

            [

                'name' => 'Admin',

                'password' => bcrypt('password'),

            ]

        );  
        // Assign role to the user with ID 1
        $user = User::find(1); // Use find() to retrieve a single user by ID

        if ($user) {
            $user->assignRole('admin'); // Now you can assign the role
        }
                
    }

}

