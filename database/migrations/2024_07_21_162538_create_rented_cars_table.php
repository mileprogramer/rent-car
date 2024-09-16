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
        Schema::create('rented_cars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('car_id');
            $table->date('start_date');
            $table->date('return_date');
            $table->integer('price_per_day');
            $table->integer('discount');
            $table->string('reason_for_discount')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rented_cars');
    }
};
