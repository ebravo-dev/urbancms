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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_for_sale')->default(true); // VENTA O RENTA
            $table->string('location_line1')->nullable(); // UBICACIÓN FILA 1
            $table->string('location_line2')->nullable(); // UBICACIÓN FILA 2
            $table->string('location_line3')->nullable(); // UBICACIÓN FILA 3
            $table->string('google_maps_url')->nullable(); // GOOGLE MAPS
            $table->string('feature1')->nullable(); // CARACTERÍSTICA 1
            $table->string('feature2')->nullable(); // CARACTERÍSTICA 2
            $table->string('feature3')->nullable(); // CARACTERÍSTICA 3
            $table->string('feature4')->nullable(); // CARACTERÍSTICA 4
            $table->string('feature5')->nullable(); // CARACTERÍSTICA 5
            $table->string('feature6')->nullable(); // CARACTERÍSTICA 6
            $table->string('feature7')->nullable(); // CARACTERÍSTICA 7
            $table->string('feature8')->nullable(); // CARACTERÍSTICA 8
            $table->decimal('investment', 15, 2)->nullable(); // INVERSIÓN
            $table->string('image1')->nullable(); // IMAGEN 1
            $table->string('image2')->nullable(); // IMAGEN 2
            $table->string('image3')->nullable(); // IMAGEN 3
            $table->string('image4')->nullable(); // IMAGEN 4
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
