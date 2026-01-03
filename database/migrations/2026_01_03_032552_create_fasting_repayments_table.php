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
        Schema::create('fasting_repayments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fasting_debt_id')->constrained()->cascadeOnDelete();
            $table->integer('paid_days');
            $table->date('repayment_date');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fasting_repayments');
    }
};
