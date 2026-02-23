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

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'dietician_number' => 'DTN12345',
        ]);

        // seed exchange values
        $this->call([\Database\Seeders\ExchangeValuesTableSeeder::class]);
        // seed exchange templates (customer template)
        $this->call([\Database\Seeders\ExchangeTemplateSeeder::class]);
    }
}
