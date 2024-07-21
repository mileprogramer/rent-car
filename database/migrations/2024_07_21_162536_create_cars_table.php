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
            $table->enum('transmission_type', ["automatic","manuel","semi-automatic",""]);
            $table->string('number_of_doors');
            $table->string('person_fit_in');
            $table->string('car_consumption');
            $table->enum('air_conditioning_type', ["Manual","Automatic","DualZone","MultiZone","RearSeat","Electric","Hybrid","SolarPowered"]);
            $table->enum('status', ["available","rented","sold","broken",""]);
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
