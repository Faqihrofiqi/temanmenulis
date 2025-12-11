<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OAuthClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if Passport is installed and tables exist
        if (!\Illuminate\Support\Facades\Schema::hasTable('oauth_clients')) {
            $this->command->warn('OAuth clients table not found. Skipping OAuth client seeding.');
            return;
        }

        // Create Personal Access Client
        DB::table('oauth_clients')->updateOrInsert(
            ['name' => 'Deadlineku Personal Access Client'],
            [
                'id' => (string) Str::uuid(),
                'owner_type' => null,
                'owner_id' => null,
                'name' => 'Deadlineku Personal Access Client',
                'secret' => Str::random(40),
                'provider' => null,
                'redirect_uris' => json_encode(['http://localhost']),
                'grant_types' => json_encode(['personal_access']),
                'revoked' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Create Password Grant Client
        DB::table('oauth_clients')->updateOrInsert(
            ['name' => 'Deadlineku Password Grant Client'],
            [
                'id' => (string) Str::uuid(),
                'owner_type' => null,
                'owner_id' => null,
                'name' => 'Deadlineku Password Grant Client',
                'secret' => Str::random(40),
                'provider' => null,
                'redirect_uris' => json_encode(['http://localhost']),
                'grant_types' => json_encode(['password']),
                'revoked' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $this->command->info('OAuth clients created successfully!');
    }
}
