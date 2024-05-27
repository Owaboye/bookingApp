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
        Schema::create('apartments', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable(false);
            $table->integer('category')->nullable(false);
            $table->string('number_of_guests')->nullable(false);
            $table->string('number_of_bedrooms')->nullable(false);
            $table->string('number_of_kitchens')->nullable(false);
            $table->decimal('amount', 12,2)->nullable(false);
            $table->string('caution_fee')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartments');
    }
};
