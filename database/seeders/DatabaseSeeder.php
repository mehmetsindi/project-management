<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create Super Admin
        User::factory()->create([
            'name' => 'Mehmet Sindi',
            'email' => 'mehmet@example.com', // Assuming email, user can change
            'password' => bcrypt('password'),
            'is_super_admin' => true,
        ]);

        // Create Test User
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call(TaskSeeder::class);
    }
}
