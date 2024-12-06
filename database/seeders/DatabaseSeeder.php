<?php

namespace Database\Seeders;

use App\Models\Ticket;
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
        $users = User::factory(10)->create();
        Ticket::factory(100)
            ->recycle($users) // will use te created 10 users and assign 100 hundred tickets to them
            ->create();

        User::factory()->create([
            'name' => 'The Manager',
            'email' => 'manager@manager.com',
            'password' => 'password',
            'is_manager' => true
        ]);
    }
}
