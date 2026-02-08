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
        Schema::table('quran_sources', function (Blueprint $table) {
            $table->integer('page')->nullable()->after('ayah_translation');
            $table->integer('juz')->nullable()->after('page');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quran_sources', function (Blueprint $table) {
            $table->dropColumn(['page', 'juz']);
        });
    }
};
