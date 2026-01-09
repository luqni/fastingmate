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
        Schema::create('quran_sources', function (Blueprint $table) {
            $table->id();
            $table->string('surah_name');
            $table->integer('ayah_number');
            $table->text('ayah_text_arabic');
            $table->text('ayah_translation');
            $table->timestamps();
        });

        Schema::create('daily_tadabburs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quran_source_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->longText('reflection')->nullable();
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->timestamps();
            
            $table->unique(['user_id', 'date']);
        });

        Schema::create('ramadan_journals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->longText('content');
            $table->string('type')->default('summary'); // summary, full, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ramadan_journals');
        Schema::dropIfExists('daily_tadabburs');
        Schema::dropIfExists('quran_sources');
    }
};
