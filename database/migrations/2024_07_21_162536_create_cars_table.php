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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('license', 20);
            $table->string('model', 100);
            $table->string('brand', 100);
            $table->string('year');
            $table->integer('price_per_day');
            $table->enum('transmission_type', \App\Enums\TransmissionType::values());
            $table->integer('number_of_doors');
            $table->integer('person_fit_in');
            $table->integer('car_consumption');
            $table->enum('air_conditioning_type', \App\Enums\AirConditionerType::values());
            $table->enum('status', \App\Enums\CarStatus::values());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
