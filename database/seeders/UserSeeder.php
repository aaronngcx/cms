<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@mail.com',
                'role' => 'Admin',
            ],
            [
                'name' => 'Author',
                'email' => 'author@mail.com',
                'role' => 'Author',
            ],
            [
                'name' => 'Editor',
                'email' => 'editor@mail.com',
                'role' => 'Editor',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => bcrypt('password'),
                ]
            );

            $role = Role::where('name', $userData['role'])->first();

            if ($role) {
                $user->roles()->syncWithoutDetaching($role->id);
            }
        }
    }
}
