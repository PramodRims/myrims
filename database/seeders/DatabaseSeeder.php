<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $roles = ['superadmin', 'admin', 'instructor', 'student'];
        foreach ($roles as $role) {
            \Spatie\Permission\Models\Role::create(['name' => $role]);
        }
        $superadmin = User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'superadmin@yopmail.com',
            'password' => bcrypt('password'),
        ]);

        $superadmin->assignRole('superadmin');
        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@yopmail.com',
            'password' => bcrypt('password'),
        ]);

        $admin->assignRole('admin');
        $instructor = User::create([
            'first_name' => 'Instructor',
            'last_name' => 'User',
            'email' => 'instructor@yopmail.com',
            'password' => bcrypt('password'),
        ]);

        $instructor->assignRole('instructor');
        $student = User::create([
            'first_name' => 'Student',
            'last_name' => 'User1',
            'email' => 'student@yopmail.com',
            'password' => bcrypt('password'),
        ]);
        $student1 = User::create([
            'first_name' => 'Student2',
            'last_name' => 'User2',
            'email' => 'student2@yopmail.com',
            'password' => bcrypt('password'),
        ]);

        $student1->assignRole('student');
        $student->assignRole('student');
    }
}
