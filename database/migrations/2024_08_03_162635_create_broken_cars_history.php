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
        Schema::create('broken_cars_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_car');
            $table->date('start_date');
            $table->date('return_date');
            $table->float('cost');
            $table->string('report');
            $table->timestamps();

            $table->foreign('id_car')->references('id')->on('cars')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('broken_cars_history');
    }
};
