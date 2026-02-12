<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('push_enabled_at')->nullable()->after('remember_token');
        });

        // Backfill for existing users who already have subscriptions
        $users = User::has('pushSubscriptions')->get();
        foreach ($users as $user) {
            $user->update(['push_enabled_at' => now()]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('push_enabled_at');
        });
    }
};
