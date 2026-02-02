<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PushSubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user (or create one if doesn't exist)
        $user = User::first();
        
        if (!$user) {
            $this->command->error('No users found. Please create a user first.');
            return;
        }

        // Dummy push subscription data for testing
        // This is a valid format but won't actually send notifications
        // For real testing, you need to subscribe from the browser
        DB::table('push_subscriptions')->insert([
            'subscribable_type' => 'App\\Models\\User',
            'subscribable_id' => $user->id,
            'endpoint' => 'https://fcm.googleapis.com/fcm/send/dummy-endpoint-for-testing',
            'public_key' => 'BPYmxA9WtyVpz4WHwR-BLzewEK9HS39WHjbXZspgfDTrmXxcgJiOvZ1TYygrJmBfBLML9R2HiimqLFruz9PpR-k',
            'auth_token' => 'dummy-auth-token-for-testing',
            'content_encoding' => 'aesgcm',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info("✅ Created dummy push subscription for user: {$user->email}");
        $this->command->warn("⚠️  Note: This is dummy data. Real notifications won't be sent.");
        $this->command->info("💡 To test real notifications, subscribe from the browser.");
    }
}
