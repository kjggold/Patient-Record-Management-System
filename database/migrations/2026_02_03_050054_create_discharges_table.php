<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('discharges', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_id');
            $table->string('patient_name');
            $table->string('doctor_name');
            $table->json('services'); // store services as JSON
            $table->decimal('total', 10, 2);
            $table->decimal('paid', 10, 2);
            $table->decimal('balance', 10, 2);
            $table->string('payment_method');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discharges');
    }
};