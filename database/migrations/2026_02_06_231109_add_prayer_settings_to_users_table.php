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
        Schema::table('users', function (Blueprint $table) {
            $table->string('prayer_city')->nullable()->after('email');
            $table->string('prayer_country')->nullable()->after('prayer_city');
            $table->integer('prayer_method')->default(2)->after('prayer_country'); // 2 = ISNA
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['prayer_city', 'prayer_country', 'prayer_method']);
        });
    }
};
