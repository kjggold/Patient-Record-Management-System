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
            // $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');

            $table->unsignedBigInteger('patient_name');
            $table->foreign('patient_name')
                ->references('id')
                ->on('patients')
                ->onDelete('cascade');

            // $table->foreignId('appointment_id')->constrained('appointments')->onDelete('cascade');
            $table->unsignedBigInteger('appointment_id');
            $table->foreign('appointment_id')
                ->references('id')
                ->on('appointments')
                ->onDelete('cascade');

            $table->unsignedBigInteger('service');
            $table->foreign('service')
                ->references('id')
                ->on('services')
                ->onDelete('cascade');
            // $table->foreignId('service_id')->constrained()->onDelete('cascade');


            $table->unsignedBigInteger('consultation_fee');
            $table->foreign('consultation_fee')
                ->references('id')
                ->on('doctors')
                ->onDelete('cascade');
            // $table->foreignId('doctor_id')->constrained()->onDelete('cascade');


            $table->string('service_fee');//fk
            // $table->foreign('service_fee')
            //     ->references('service_fee')
            //     ->on('services')
            //     ->onDelete('cascade');


            $table->string('payment_method')->default('Cash');
            $table->string('remarks');
            $table->string('status')->default('Pending');
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