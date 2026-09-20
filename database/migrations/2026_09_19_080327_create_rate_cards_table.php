<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rate_cards', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // "Wash & Fold"
            $table->string('service_type')->unique();        // "wash_fold"
            $table->decimal('base_rate', 8, 2);              // fallback rate per item
            $table->json('tiers')->nullable();               // [{min_quantity, rate}, ...]
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rate_cards');
    }
};