<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discharges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained('appointments')->onDelete('cascade');
            $table->json('services'); // store all services as JSON
            $table->decimal('total', 10, 2);
            $table->decimal('discount', 10, 2);
            $table->decimal('paid', 10, 2);
            $table->decimal('balance', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discharges');
    }
};
