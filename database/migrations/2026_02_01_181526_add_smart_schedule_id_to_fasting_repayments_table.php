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
        Schema::table('fasting_repayments', function (Blueprint $table) {
            $table->foreignId('smart_schedule_id')->nullable()->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fasting_repayments', function (Blueprint $table) {
            $table->dropForeign(['smart_schedule_id']);
            $table->dropColumn('smart_schedule_id');
        });
    }
};
