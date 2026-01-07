<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('visits');
        
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ip_address', 45)->nullable(); // IPv4 or IPv6
            $table->date('visit_date');
            $table->timestamps();

            // Index for faster queries on date ranges
            $table->index('visit_date');
            // Index for preventing duplicate visits per user/ip per day
            $table->unique(['user_id', 'visit_date', 'ip_address'], 'unique_visit'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
