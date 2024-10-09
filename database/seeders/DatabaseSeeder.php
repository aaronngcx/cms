<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Admin'],
            ['name' => 'Editor'],
            ['name' => 'Author'],
            // Add more roles as needed
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']]);
        }

        User::factory(1)->create()->each(function ($user) {
            $roles = Role::all()->random(rand(1, 3));
            $user->roles()->attach($roles);
        });

        $admin = User::where('name', 'Admin')->first();
        if (!$admin) {
            User::factory()->create([
                'name' => 'Admin',
                'email' => 'admin@mail.com',
                'password' => bcrypt('password'),
            ]);
        }

        // Create random posts
        Post::factory()->count(5)->create();
    }
}
