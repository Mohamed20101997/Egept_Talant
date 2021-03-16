<?php

use Illuminate\Database\Seeder;
use App\Role;
use \App\User;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $roles  = ['Admin', 'Teacher' , 'Student', 'Support' , 'Secretary'];

        foreach($roles as $role){
            Role::create([
                'name' => $role ,
                'is_staff' => 0,
                'is_teacher' => 0,
            ]);
        }

        User::create([
            'name' => 'Amdin',
            'email' => 'admin@gmail.com',
            'phone' => '01015127991',
            'password' => Hash::Make('123456'),
            'role_id' => 1,
        ]);

        // $this->call(UserSeeder::class);
    }
}
