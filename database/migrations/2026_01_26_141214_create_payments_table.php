<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
    $table->id();
    $table->string('appointment_id');
    $table->string('patient_name');
    $table->string('service')->nullable();
    $table->decimal('total', 10, 2);
    $table->decimal('discount', 10, 2)->default(0);
    $table->decimal('paid', 10, 2);
    $table->string('payment_method');
    $table->text('remarks')->nullable();
    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
