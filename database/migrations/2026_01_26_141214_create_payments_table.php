<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /* Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('patient_name');//fk
            $table->foreign('patient_name')
                ->references('full_name')
                ->on('patients')
                ->onDelete('cascade');

            $table->foreignId('appointment_id')->constrained('appointments')->onDelete('cascade');
            // $table->integer('appointment_id');//fk
            // $table->foreign('appointment_id')
            //     ->references('id')
            //     ->on('appointments')
            //     ->onDelete('cascade');

            $table->string('service');//fk
            $table->foreign('service')
                ->references('service_name')
                ->on('services')
                ->onDelete('cascade');

            $table->string('consultation_fee');//fk
            $table->foreign('consultation_fee')
                ->references('consultation_fee')
                ->on('doctors')
                ->onDelete('cascade');

            $table->string('service_fee');//fk
            $table->foreign('service_fee')
                ->references('service_fee')
                ->on('services')
                ->onDelete('cascade');

            $table->string('payment_method');
            $table->string('remarks');
            $table->string('status');
            $table->timestamps();
        });
    }

    /* Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};