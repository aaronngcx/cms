<?php

namespace Database\Seeders;

use RoleSeeder;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
        ]);
        User::factory()->count(5)->create();

        Post::factory()->count(5)->create();
    }
}
