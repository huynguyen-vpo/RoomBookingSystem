<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Uuid;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('available_quantities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->dateTime('date')->unique();
            $table->integer('single_remaining_quantity')->default(0);
            $table->integer('double_remaining_quantity')->default(0);
            $table->integer('triple_remaining_quantity')->default(0);
            $table->integer('quarter_remaining_quantity')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('available_quantities');
    }
};
