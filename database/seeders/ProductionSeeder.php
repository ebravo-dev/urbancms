<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    /**
     * Seed the application's database for production/client environment.
     * Only includes essential data for client testing.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
        ]);

        $this->command->info('Production seeding completed!');
        $this->command->info('Only admin user has been created for client testing.');
    }
}
