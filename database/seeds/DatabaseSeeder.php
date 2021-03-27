<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;
use  App\ExamType;
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

        $examTypes  = ['True&False', 'Choices' , 'Essays'];
        foreach($examTypes as $examType){
           ExamType::create([
                'name' => $examType ,
            ]);
        }


        User::create([
            'name' => 'Amdin',
            'email' => 'admin1@gmail.com',
            'phone' => '01015127991',
            'password' => Hash::Make('123456'),
            'role_id' => 1,
        ]);

        User::create([
            'name' => 'Teacher',
            'email' => 'teacher1@gmail.com',
            'phone' => '01015127991',
            'password' => Hash::Make('123456'),
            'role_id' => 2,
        ]);
        User::create([
            'name' => 'Student',
            'email' => 'student1@gmail.com',
            'phone' => '01015127991',
            'password' => Hash::Make('123456'),
            'role_id' => 3,
        ]);

        // $this->call(UserSeeder::class);
    }
}
