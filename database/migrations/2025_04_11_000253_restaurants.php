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
    Schema::create('restaurants', function (Blueprint $table) {
        $table->id();
        $table->string('restaurant_name')->unique();
        $table->string('restaurant_description')->nullable();
        $table->string('favorite_meal')->nullable();
        $table->string('file')->nullable();
        $table->unsignedBigInteger('sauce_id')->nullable(); // For sauce
        $table->unsignedBigInteger('chicken_type_id')->nullable(); // Add chicken_type_id here
        $table->timestamps();

        $table->foreign('sauce_id')->references('id')->on('sauces')->onDelete('set null');
        $table->foreign('chicken_type_id')->references('id')->on('chicken_types')->onDelete('cascade'); // Link to chicken_types table
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
